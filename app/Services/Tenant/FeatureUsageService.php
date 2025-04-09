<?php

namespace App\Services\Tenant;

use App\Models\Activity;
use App\Models\Feature;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class FeatureUsageService
{
    // Limit eşik değerleri
    const LIMIT_THRESHOLDS = [50, 75, 90, 95];

    // Kullanım aktiviteleri için mesaj
    const USAGE_MESSAGE = 'feature.usage';
    const LIMIT_THRESHOLD_MESSAGE = 'feature.limit_threshold_reached';

    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected FeatureAccessService $featureAccessService,
        protected NotificationService $notificationService
    ) {
    }

    /**
     * Tenant'ın belirli bir özellik için kalan kullanım sayısını kontrol eder
     */
    public function getRemainingUsage(Tenant $tenant, string $featureSlug): int
    {
        // Önce feature'ı bul
        $feature = $this->featureAccessService->getFeatureBySlug($featureSlug);
        if (!$feature) {
            return 0;
        }

        // Aktif aboneliği al
        $activeSubscription = $this->subscriptionService->getActiveSubscription($tenant);
        if (!$activeSubscription) {
            return 0;
        }

        // Aboneliğin planının özelliklerini kontrol et
        $planFeature = $activeSubscription->plan->getFeature($feature->id);

        // Eğer plan bu özelliği içermiyorsa veya özellik erişim tabanlıysa
        if (!$planFeature || $planFeature->isAccessOnly()) {
            return 0;
        }

        // Sınırsız kullanım varsa
        if ($planFeature->hasUnlimitedUsage()) {
            return -1; // -1 sınırsız anlamına gelir
        }

        // Kullanım limitini kontrol et
        if ($planFeature->isLimited()) {
            $limit = $planFeature->limit_value;

            // Kullanılan miktarı al
            $usedCount = $this->getUsageCount($tenant->id, $feature->id, $planFeature);

            return max(0, $limit - $usedCount);
        }

        return 0;
    }

    /**
     * Tenant'ın belirli bir özellik için kullanım sayısını artırır
     */
    public function incrementUsage(Tenant $tenant, string $featureSlug, int $amount = 1): bool
    {
        // Önce feature'ı bul
        $feature = $this->featureAccessService->getFeatureBySlug($featureSlug);
        if (!$feature) {
            return false;
        }

        // Aktif aboneliği al
        $activeSubscription = $this->subscriptionService->getActiveSubscription($tenant);
        if (!$activeSubscription) {
            return false;
        }

        // Aboneliğin planının özelliklerini kontrol et
        $planFeature = $activeSubscription->plan->getFeature($feature->id);

        // Eğer plan bu özelliği içermiyorsa veya özellik erişim tabanlıysa
        if (!$planFeature || $planFeature->isAccessOnly()) {
            return false;
        }

        // Sınırsız kullanım varsa işlem yapmaya gerek yok
        if ($planFeature->hasUnlimitedUsage()) {
            return true;
        }

        // Kullanım limitini kontrol et
        if ($planFeature->isLimited()) {
            $cacheKey = $this->getCacheKey($tenant->id, $feature->id);

            // Mevcut kullanımı al
            $currentUsage = Cache::get($cacheKey, 0);

            // Yeni kullanım miktarı
            $newUsageCount = $currentUsage + $amount;

            // Kullanımı artır
            Cache::put($cacheKey, $newUsageCount, $this->getCacheTTL($planFeature));

            // Kullanım kaydını activity loguna ekle
            $this->logFeatureUsage($tenant->id, $feature->id, $amount);

            // Limit eşiklerini kontrol et
            $this->checkLimitThresholds($tenant, $feature, $planFeature, $newUsageCount);

            return true;
        }

        return false;
    }

    /**
     * Özellik kullanım miktarını getirir
     */
    public function getUsageCount(int $tenantId, int $featureId, $planFeature): int
    {
        $cacheKey = $this->getCacheKey($tenantId, $featureId);
        return Cache::get($cacheKey, 0);
    }

    /**
     * Cache anahtarını oluşturur
     */
    private function getCacheKey(int $tenantId, int $featureId): string
    {
        return "tenant:{$tenantId}:feature:{$featureId}:usage";
    }

    /**
     * Özellik kullanım cache süresi
     */
    private function getCacheTTL($planFeature)
    {
        if ($planFeature->isRenewable()) {
            // Yenilenebilir limit için sıfırlama periodu belirle
            switch ($planFeature->limit_reset_period) {
                case 'hourly':
                    return now()->addHour();
                case 'daily':
                    return now()->addDay();
                case 'weekly':
                    return now()->addWeek();
                case 'monthly':
                    return now()->addMonth();
                case 'yearly':
                    return now()->addYear();
                default:
                    return now()->addMonth(); // Varsayılan olarak aylık
            }
        }

        // Birikimli limit için, abonelik süresi boyunca sakla
        return now()->addYear(); // Varsayılan olarak 1 yıl
    }

    /**
     * Kullanım kaydını activity loguna ekler
     */
    private function logFeatureUsage(int $tenantId, int $featureId, int $amount): void
    {
        // Özelliği getir
        $feature = $this->featureAccessService->getFeatureById($featureId);
        if (!$feature) return;

        Activity::create([
            'tenant_id' => $tenantId,
            'message' => self::USAGE_MESSAGE,
            'log' => [
                'action' => 'feature_usage',
                'feature_id' => $featureId,
                'feature_name' => $feature->name,
                'feature_slug' => $feature->slug,
                'amount' => $amount,
                'created_at' => now()->toDateTimeString()
            ],
        ]);
    }

    /**
     * Kullanım raporu oluşturur
     *
     * @param Tenant $tenant
     * @param string|null $featureSlug Belirli bir özellik için rapor almak isterseniz
     * @param Carbon $startDate Başlangıç tarihi
     * @param Carbon $endDate Bitiş tarihi
     * @return array
     */
    public function getUsageReport(Tenant $tenant, ?string $featureSlug = null, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        // Varsayılan tarih aralığı - son 30 gün
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        // Aktif aboneliği al
        $activeSubscription = $this->subscriptionService->getActiveSubscription($tenant);
        if (!$activeSubscription) {
            return [];
        }

        // Tüm özellikler veya belirli bir özellik
        $features = [];
        if ($featureSlug) {
            $feature = $this->featureAccessService->getFeatureBySlug($featureSlug);
            if ($feature) {
                $features[] = $feature;
            }
        } else {
            // Tüm plan özelliklerini al
            $planFeatures = $activeSubscription->plan->planFeatures;
            $features = $planFeatures->map(function ($planFeature) {
                return $planFeature->feature;
            });
        }

        // Rapor verilerini hazırla
        $report = [];
        foreach ($features as $feature) {
            $planFeature = $activeSubscription->plan->getFeature($feature->id);
            if (!$planFeature) continue;

            // Mevcut kullanım
            $currentUsage = $this->getUsageCount($tenant->id, $feature->id, $planFeature);

            // Kullanım geçmişi
            $usageHistory = $this->getUsageHistory($tenant->id, $feature->id, $startDate, $endDate);

            // Limit bilgisi
            $limitInfo = [
                'is_limited' => $planFeature->isLimited(),
                'is_access_only' => $planFeature->isAccessOnly(),
                'is_unlimited' => $planFeature->hasUnlimitedUsage(),
                'limit_value' => $planFeature->limit_value ?? null,
                'limit_type' => $planFeature->limit_type ?? null,
                'limit_reset_period' => $planFeature->limit_reset_period ?? null,
            ];

            // Raporu hazırla
            $report[$feature->slug] = [
                'feature_id' => $feature->id,
                'feature_name' => $feature->name,
                'feature_slug' => $feature->slug,
                'current_usage' => $currentUsage,
                'limit_info' => $limitInfo,
                'usage_history' => $usageHistory,
                'usage_percentage' => $planFeature->isLimited() && !$planFeature->hasUnlimitedUsage() && $planFeature->limit_value > 0
                    ? round(($currentUsage / $planFeature->limit_value) * 100, 1)
                    : null,
            ];
        }

        return $report;
    }

    /**
     * Kullanım geçmişini getirir
     *
     * @param int $tenantId
     * @param int $featureId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getUsageHistory(int $tenantId, int $featureId, Carbon $startDate, Carbon $endDate): array
    {
        // Activity loglarından kullanım verilerini çek
        $dailyUsage = Activity::where('tenant_id', $tenantId)
            ->where('message', self::USAGE_MESSAGE)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereJsonContains('log->feature_id', $featureId)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            })
            ->map(function ($group) {
                return $group->sum(function ($item) {
                    return $item->log['amount'] ?? 0;
                });
            });

        // Tüm günleri içeren boş bir dizi oluştur
        $allDates = [];
        $current = clone $startDate;
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $allDates[$dateStr] = 0;
            $current->addDay();
        }

        // Verileri birleştir
        foreach ($dailyUsage as $date => $usage) {
            $allDates[$date] = $usage;
        }

        // Sonuç formatını hazırla
        $result = [];
        foreach ($allDates as $date => $usage) {
            $result[] = [
                'date' => $date,
                'usage' => $usage,
            ];
        }

        return $result;
    }

    /**
     * Limit eşiklerini kontrol eder ve gerekirse bildirim gönderir
     *
     * @param Tenant $tenant
     * @param Feature $feature
     * @param mixed $planFeature
     * @param int $currentUsage
     */
    public function checkLimitThresholds(Tenant $tenant, Feature $feature, $planFeature, int $currentUsage): void
    {
        // Sınırsız kullanım veya erişim bazlı özellikse kontrole gerek yok
        if ($planFeature->hasUnlimitedUsage() || $planFeature->isAccessOnly()) {
            return;
        }

        $limit = $planFeature->limit_value;
        if ($limit <= 0) return;

        // Kullanım yüzdesi
        $usagePercentage = ($currentUsage / $limit) * 100;

        // Her bir eşik için kontrol et
        foreach (self::LIMIT_THRESHOLDS as $threshold) {
            if ($usagePercentage >= $threshold) {
                $thresholdKey = $this->getLimitThresholdKey($tenant->id, $feature->id, $threshold);

                // Bu eşik daha önce bildirildi mi?
                if (!Cache::has($thresholdKey)) {
                    // Bildirimi işaretle
                    Cache::put($thresholdKey, true, $this->getCacheTTL($planFeature));

                    // Etkinliği kaydet
                    $this->logLimitThresholdReached($tenant, $feature, $threshold, $currentUsage, $limit);

                    // Tenant'ın owner kullanıcısını bul ve bildirim gönder
                    $owner = $tenant->users()->whereType('tenant_owner')->first();
                    if ($owner) {
                        $this->notificationService->notifyFeatureLimitWarning(
                            $feature,
                            $owner,
                            $threshold,
                            $currentUsage,
                            $limit
                        );
                    }
                }
            }
        }
    }

    /**
     * Limit eşiği için önbellek anahtarı oluşturur
     */
    private function getLimitThresholdKey(int $tenantId, int $featureId, int $threshold): string
    {
        return "tenant:{$tenantId}:feature:{$featureId}:threshold:{$threshold}";
    }

    /**
     * Limit eşiğine ulaşıldığında etkinlik kaydeder
     */
    private function logLimitThresholdReached(Tenant $tenant, Feature $feature, int $threshold, int $currentUsage, int $limit): void
    {
        Activity::create([
            'tenant_id' => $tenant->id,
            'message' => self::LIMIT_THRESHOLD_MESSAGE,
            'log' => [
                'action' => 'limit_threshold_reached',
                'feature_id' => $feature->id,
                'feature_name' => $feature->name,
                'feature_slug' => $feature->slug,
                'threshold' => $threshold,
                'current_usage' => $currentUsage,
                'limit' => $limit,
                'percentage' => round(($currentUsage / $limit) * 100, 1),
                'created_at' => now()->toDateTimeString()
            ],
        ]);
    }
}
