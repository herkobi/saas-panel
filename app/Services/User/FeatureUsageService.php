<?php

namespace App\Services\User;

use LucasDotVin\Soulbscription\Models\Feature;
use Illuminate\Support\Collection;
use App\Services\LoggingService;
use App\Models\User;

class FeatureUsageService
{
    protected LoggingService $loggingService;

    public function __construct(LoggingService $loggingService)
    {
        $this->loggingService = $loggingService;
    }

    /**
     * Kullanım istatistiklerini getirir.
     * Toplam kullanım, ortalama, kalan kota gibi bilgileri içerir.
     */
    public function getUsageStats(User $user, string $featureCode): array
    {
        $usages = $user->subscriptionFeatureUsages()
            ->where('feature_code', $featureCode)
            ->get();

        return [
            'total_usage' => $usages->sum('used_amount'),
            'average_usage' => $usages->avg('used_amount'),
            'last_used' => $usages->last()?->created_at,
            'usage_count' => $usages->count(),
            'remaining_quota' => $user->getRemainingCharges($featureCode)
        ];
    }

    /**
     * Kullanım geçmişini getirir
     */
    public function getUsageHistory(User $user, string $featureCode): Collection
    {
        return $user->subscriptionFeatureUsages()
            ->where('feature_code', $featureCode)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Özelliği askıya alır
     */
    public function suspendFeature(User $user, string $featureCode): bool
    {
        $user->subscription->features()
            ->where('code', $featureCode)
            ->update(['suspended' => true]);

        $this->loggingService->logUserAction(
            'feature.suspended',
            $user,
            Feature::where('code', $featureCode)->first()
        );

        return true;
    }

    /**
     * Askıya alınmış özelliği aktif eder
     */
    public function resumeFeature(User $user, string $featureCode): bool
    {
        $user->subscription->features()
            ->where('code', $featureCode)
            ->update(['suspended' => false]);

        $this->loggingService->logUserAction(
            'feature.resumed',
            $user,
            Feature::where('code', $featureCode)->first()
        );

        return true;
    }

    /**
     * Özellik kullanımında log kaydı oluşturur
     */
    public function logFeatureUsage(User $user, string $featureCode, float $amount): void
    {
        $this->loggingService->logUserAction(
            'feature.consumed',
            $user,
            Feature::where('code', $featureCode)->first(),
            ['amount' => $amount]
        );
    }
}
