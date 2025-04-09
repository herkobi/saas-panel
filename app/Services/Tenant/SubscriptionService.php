<?php

namespace App\Services\Tenant;

use App\Enums\SubscriptionStatus;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionService
{
    /**
     * Tüm planları getir
     */
    public function getAllPlans(): Collection
    {
        return Plan::with('planFeatures.feature')
            ->latest()
            ->get();
    }

    /**
     * Sayfalanmış planları getir
     */
    public function getPaginatedPlans(int $perPage = 15): LengthAwarePaginator
    {
        return Plan::with('planFeatures.feature')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Aktif planları getir
     */
    public function getActivePlans(): Collection
    {
        return Plan::where('status', true)
            ->with('planFeatures.feature')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Detaylı plan bilgisini getir
     */
    public function getPlanWithDetails(Plan $plan): Plan
    {
        return $plan->load('planFeatures.feature');
    }

    /**
     * Tenant'ın aktif aboneliğini getir
     */
    public function getActiveSubscription(Tenant $tenant): ?Subscription
    {
        return $tenant->subscriptions()
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->where(function ($query) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>', now());
            })
            ->latest()
            ->first();
    }

    /**
     * Tenant'ın tüm aboneliklerini getir
     */
    public function getTenantSubscriptions(Tenant $tenant): Collection
    {
        return $tenant->subscriptions()
            ->with('plan')
            ->latest()
            ->get();
    }

    /**
     * Yeni abonelik oluştur
     */
    public function createSubscription(Tenant $tenant, Plan $plan, array $data): Subscription
    {
        $billingPeriod = $data['billing_period'] ?? 'monthly';
        $price = $billingPeriod === 'yearly' ? $plan->yearly_price : $plan->monthly_price;

        // Vergi hesaplamaları
        $taxRate = 0;
        $taxRates = config('tenant.tax_rates', []);
        $taxRateConfig = collect($taxRates)->firstWhere('code', $plan->tax_rate_code);
        if ($taxRateConfig) {
            $taxRate = $taxRateConfig['rate'];
        }
        $taxAmount = $price * ($taxRate / 100);
        $totalAmount = $price + $taxAmount;

        // Abonelik bitiş tarihi
        $endsAt = null;
        if (!$plan->is_free) {
            $endsAt = $billingPeriod === 'yearly'
                ? now()->addYear()
                : now()->addMonth();
        }

        // Deneme süresi
        $trialEndsAt = $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null;

        // Grace period
        $graceEndsAt = $endsAt ? $endsAt->copy()->addDays($plan->grace_period_days) : null;

        // Aboneliği oluştur
        $subscription = new Subscription([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'name' => $plan->name,
            'trial_ends_at' => $trialEndsAt,
            'ends_at' => $endsAt,
            'grace_ends_at' => $graceEndsAt,
            'next_billing_at' => $endsAt,
            'billing_period' => $billingPeriod,
            'price' => $price,
            'currency_code' => $plan->currency_code,
            'tax_rate_code' => $plan->tax_rate_code,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'is_recurring' => !$plan->is_free,
            'status' => SubscriptionStatus::ACTIVE,
        ]);

        $subscription->save();

        // Ücretsiz plan değilse ödeme kaydı oluştur
        if (!$plan->is_free) {
            $this->createPaymentRecord(
                $subscription,
                $data,  // Billing data
                $price, // Base amount
                $taxAmount,
                $totalAmount,
                $plan->currency_code
            );
        }

        return $subscription;
    }

    /**
     * Plan değiştirme işlemi - Hemen değişim
     */
    public function changePlanImmediately(Subscription $subscription, Plan $newPlan, string $billingPeriod = null): Subscription
    {
        // Mevcut billingPeriod'u kullan ya da sağlanan değeri al
        $billingPeriod = $billingPeriod ?: $subscription->billing_period;

        // Eski aboneliği iptal et
        $subscription->status = SubscriptionStatus::CANCELLED;
        $subscription->cancelled_at = now();
        $subscription->save();

        // Yeni abonelik için basitleştirilmiş veri
        $data = [
            'billing_period' => $billingPeriod,
        ];

        // Yeni abonelik oluştur
        return $this->createSubscription($subscription->tenant, $newPlan, $data);
    }

    /**
     * Plan değiştirmeyi dönem sonuna planla
     */
    public function schedulePlanChange(Subscription $subscription, Plan $newPlan, string $billingPeriod = null): bool
    {
        // Mevcut billingPeriod'u kullan ya da sağlanan değeri al
        $billingPeriod = $billingPeriod ?: $subscription->billing_period;

        $subscription->scheduled_plan_id = $newPlan->id;
        $subscription->change_scheduled_at = $subscription->ends_at;

        // Billing period değişikliğini takip et
        if ($billingPeriod !== $subscription->billing_period) {
            $subscription->fill([
                'billing_period' => $billingPeriod,
            ]);
        }

        return $subscription->save();
    }

    /**
     * Orantılı fiyat hesaplama (Pro-rata)
     */
    public function calculateProratedAmount(Subscription $subscription, Plan $newPlan): array
    {
        // Şimdiki tarih ile dönem sonu arasındaki gün sayısı
        $daysLeft = now()->diffInDays($subscription->ends_at);

        // Toplam dönem gün sayısı (30 gün veya 365 gün varsayalım)
        $totalDays = $subscription->billing_period === 'monthly' ? 30 : 365;

        // Kullanılmayan gün oranı
        $unusedRatio = $daysLeft / $totalDays;

        // Eski planın kalan değeri
        $remainingValue = $subscription->price * $unusedRatio;

        // Yeni planın fiyatı
        $newPrice = $subscription->billing_period === 'monthly' ? $newPlan->monthly_price : $newPlan->yearly_price;

        // Yeni planın orantılı değeri
        $proratedNewPrice = $newPrice * $unusedRatio;

        // Müşteriye fatura edilecek fark
        $amountToCharge = $proratedNewPrice - $remainingValue;

        // Vergi hesaplaması
        $taxRate = 0;
        $taxRates = config('tenant.tax_rates', []);
        $taxRateConfig = collect($taxRates)->firstWhere('code', $newPlan->tax_rate_code);
        if ($taxRateConfig) {
            $taxRate = $taxRateConfig['rate'];
        }

        $taxAmount = $amountToCharge * ($taxRate / 100);
        $totalAmount = $amountToCharge + $taxAmount;

        return [
            'days_left' => $daysLeft,
            'total_days' => $totalDays,
            'unused_ratio' => $unusedRatio,
            'remaining_value' => $remainingValue,
            'prorated_new_price' => $proratedNewPrice,
            'amount_to_charge' => $amountToCharge,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Planlanmış değişiklikleri işle
     *
     * Not: Bu metod artık Schedule tarafından çağrılacak, RenewSubscriptionsCommand ve
     * ProcessScheduledSubscriptionChangesCommand tarafından kullanılacak.
     */
    public function processScheduledChanges(): void
    {
        // Planlanmış plan değişiklikleri işle
        Subscription::query()
            ->whereNotNull('scheduled_plan_id')
            ->whereNotNull('change_scheduled_at')
            ->where('change_scheduled_at', '<=', now())
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->each(function (Subscription $subscription) {
                $newPlan = Plan::find($subscription->scheduled_plan_id);
                if ($newPlan) {
                    $this->changePlanImmediately($subscription, $newPlan);
                }
            });

        // Planlanmış iptalleri işle
        Subscription::query()
            ->whereNotNull('cancellation_scheduled_at')
            ->where('cancellation_scheduled_at', '<=', now())
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->each(function (Subscription $subscription) {
                $this->cancelSubscription($subscription);
            });
    }

    /**
     * Gecikmeli abonelik iptali planla
     */
    public function scheduleCancellation(Subscription $subscription, string $reason = null): bool
    {
        $subscription->cancellation_scheduled_at = $subscription->ends_at;

        if ($reason) {
            $subscription->cancellation_reason = $reason;
        }

        return $subscription->save();
    }

    /**
     * Aboneliği iptal et
     */
    public function cancelSubscription(Subscription $subscription): bool
    {
        $subscription->cancelled_at = now();
        $subscription->status = SubscriptionStatus::CANCELLED;
        return $subscription->save();
    }

    /**
     * Aboneliği yenile
     */
    public function renewSubscription(Subscription $subscription): Subscription
    {
        // Eski aboneliği kapatmadan yeni abonelik oluşturma
        $plan = $subscription->plan;
        $tenant = $subscription->tenant;
        $billingPeriod = $subscription->billing_period;

        return $this->createSubscription($tenant, $plan, [
            'billing_period' => $billingPeriod
        ]);
    }

    /**
     * Ödeme kaydı oluştur
     */
    private function createPaymentRecord(Subscription $subscription, array $billingData, float $amount, float $taxAmount, float $totalAmount, string $currencyCode): void
    {
        Payment::create([
            'tenant_id' => $subscription->tenant_id,
            'subscription_id' => $subscription->id,
            'payment_method' => 'credit_card', // Varsayılan metod, gerçek entegrasyonda değişebilir
            'transaction_id' => 'sim_' . Str::random(20), // Simüle edilmiş transaction ID
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'currency_code' => $currencyCode,
            'status' => 'succeeded', // Başarılı ödeme
            'paid_at' => now(),

            // Fatura bilgileri
            'billing_name' => $billingData['billing_name'] ?? '',
            'billing_address' => $billingData['billing_address'] ?? '',
            'billing_city' => $billingData['billing_city'] ?? '',
            'billing_district' => $billingData['billing_district'] ?? '',
            'billing_postal_code' => $billingData['billing_postal_code'] ?? '',
            'billing_tax_office' => $billingData['billing_tax_office'] ?? '',
            'billing_tax_number' => $billingData['billing_tax_number'] ?? '',
            'billing_email' => $billingData['billing_email'] ?? '',
            'billing_contact_name' => $billingData['billing_contact_name'] ?? '',
            'billing_phone' => $billingData['billing_phone'] ?? '',

            'payment_data' => [
                'method_details' => 'Simulated Payment', // Ödeme ayrıntıları
                'billing_period' => $subscription->billing_period,
            ],
        ]);
    }

    /**
     * Yakında sona erecek abonelikler için sorgu
     * Bu metod, yakında sona erecek abonelikleri bulmak için
     * CheckExpiringSubscriptionsCommand tarafından kullanılabilir
     */
    public function getExpiringSubscriptions(int $daysThreshold = 3): Collection
    {
        return Subscription::query()
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->whereNull('cancelled_at')
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [
                now(),
                now()->addDays($daysThreshold)
            ])
            ->get();
    }

    /**
     * Bugün yenilenmesi gereken abonelikler
     * Bu metod, yenilenecek abonelikleri bulmak için
     * RenewSubscriptionsCommand tarafından kullanılabilir
     */
    public function getSubscriptionsDueForRenewal(): Collection
    {
        return Subscription::query()
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->where('is_recurring', true)
            ->whereNull('cancelled_at')
            ->whereNull('cancellation_scheduled_at')
            ->whereNotNull('ends_at')
            ->whereDate('ends_at', '<=', now())
            ->get();
    }
}
