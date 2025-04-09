<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Subscription extends Model
{
    use HasFactory, HasTenant;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'name',
        'trial_ends_at',
        'ends_at',
        'grace_ends_at',
        'next_billing_at',
        'cancelled_at',
        'cancellation_scheduled_at',
        'cancellation_reason',
        'scheduled_plan_id',
        'change_scheduled_at',
        'billing_period',
        'price',
        'currency_code',
        'tax_rate_code',
        'tax_amount',
        'total_amount',
        'is_recurring',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'ends_at' => 'datetime',
            'grace_ends_at' => 'datetime',
            'next_billing_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'cancellation_scheduled_at' => 'datetime',
            'change_scheduled_at' => 'datetime',
            'price' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'is_recurring' => 'boolean',
            'status' => SubscriptionStatus::class,
        ];
    }

    /**
     * Aboneliğin ait olduğu tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Aboneliğin bağlı olduğu plan
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Aboneliğin değiştirilmesi planlanan plan
     */
    public function scheduledPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'scheduled_plan_id');
    }

    /**
     * Aboneliğin ödemeleri
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope: Aktif abonelikler
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::ACTIVE->value);
    }

    /**
     * Scope: İptal edilmiş abonelikler
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::CANCELLED->value);
    }

    /**
     * Scope: Süresi dolmuş abonelikler
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::EXPIRED->value);
    }

    /**
     * Scope: Beklemede olan abonelikler
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::PENDING->value);
    }

    /**
     * Scope: Yenilenebilir abonelikler
     */
    public function scopeRenewable(Builder $query): Builder
    {
        return $query->active()
            ->where('is_recurring', true)
            ->whereNull('cancelled_at')
            ->whereNull('cancellation_scheduled_at');
    }

    /**
     * Scope: Deneme süresinde olan abonelikler
     */
    public function scopeOnTrial(Builder $query): Builder
    {
        return $query->active()
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now());
    }

    /**
     * Scope: Grace period'da olan abonelikler
     */
    public function scopeOnGracePeriod(Builder $query): Builder
    {
        return $query->active()
            ->whereNotNull('grace_ends_at')
            ->where('grace_ends_at', '>', now());
    }

    /**
     * Scope: Yakında sona erecek abonelikler
     */
    public function scopeExpiringSoon(Builder $query, int $days = 3): Builder
    {
        return $query->active()
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [now(), now()->addDays($days)]);
    }

    /**
     * Scope: Planlanmış değişiklikleri olan abonelikler
     */
    public function scopeWithScheduledChanges(Builder $query): Builder
    {
        return $query->active()
            ->whereNotNull('scheduled_plan_id')
            ->whereNotNull('change_scheduled_at');
    }

    /**
     * Scope: Planlanmış iptalleri olan abonelikler
     */
    public function scopeWithScheduledCancellations(Builder $query): Builder
    {
        return $query->active()
            ->whereNotNull('cancellation_scheduled_at');
    }

    /**
     * Scope: Bugün yenilenmesi gereken abonelikler
     */
    public function scopeDueForRenewalToday(Builder $query): Builder
    {
        return $query->renewable()
            ->whereNotNull('ends_at')
            ->whereDate('ends_at', '<=', now());
    }

    /**
     * Aboneliğin aktif olup olmadığını kontrol eder
     */
    public function isActive(): bool
    {
        return $this->status->isActive() && !$this->isExpired();
    }

    /**
     * Aboneliğin deneme süresinde olup olmadığını kontrol eder
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at !== null && now()->lt($this->trial_ends_at);
    }

    /**
     * Aboneliğin grace period içinde olup olmadığını kontrol eder
     */
    public function onGracePeriod(): bool
    {
        return $this->grace_ends_at !== null && now()->lt($this->grace_ends_at);
    }

    /**
     * Aboneliğin süresinin dolup dolmadığını kontrol eder
     */
    public function isExpired(): bool
    {
        return $this->ends_at !== null && now()->gte($this->ends_at);
    }

    /**
     * Aboneliğin süresi yakında dolacak mı kontrol eder
     */
    public function isExpiringSoon(int $days = 3): bool
    {
        return $this->ends_at !== null &&
               now()->lte($this->ends_at) &&
               now()->addDays($days)->gte($this->ends_at);
    }

    /**
     * Aboneliğin iptal edilip edilmediğini kontrol eder
     */
    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
    }

    /**
     * Aboneliğin iptali planlanmış mı kontrol eder
     */
    public function hasPendingCancellation(): bool
    {
        return $this->cancellation_scheduled_at !== null && now()->lt($this->cancellation_scheduled_at);
    }

    /**
     * Aboneliğin plan değişikliği planlanmış mı kontrol eder
     */
    public function hasPendingPlanChange(): bool
    {
        return $this->scheduled_plan_id !== null && $this->change_scheduled_at !== null && now()->lt($this->change_scheduled_at);
    }

    /**
     * Aboneliğin yenilenme tarihinin yaklaşıp yaklaşmadığını kontrol eder
     */
    public function isCloseToRenewal(int $days = 3): bool
    {
        return $this->next_billing_at !== null && now()->addDays($days)->gte($this->next_billing_at);
    }

    /**
     * Aboneliğin yenilenebilir olup olmadığını kontrol eder
     */
    public function isRenewable(): bool
    {
        return $this->status->isActive() &&
               $this->is_recurring &&
               $this->cancelled_at === null &&
               $this->cancellation_scheduled_at === null;
    }

    /**
     * Aboneliğin bugün yenilenmesi gerekip gerekmediğini kontrol eder
     */
    public function isDueForRenewalToday(): bool
    {
        return $this->isRenewable() &&
               $this->ends_at !== null &&
               now()->startOfDay()->lte($this->ends_at) &&
               now()->endOfDay()->gte($this->ends_at);
    }

    /**
     * Kalan gün sayısını döndürür
     */
    public function daysLeft(): ?int
    {
        if ($this->ends_at === null) {
            return null;
        }

        return max(0, now()->diffInDays($this->ends_at, false));
    }

    /**
     * Deneme süresinden kalan gün sayısını döndürür
     */
    public function trialDaysLeft(): ?int
    {
        if ($this->trial_ends_at === null) {
            return null;
        }

        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    /**
     * Grace period'dan kalan gün sayısını döndürür
     */
    public function graceDaysLeft(): ?int
    {
        if ($this->grace_ends_at === null) {
            return null;
        }

        return max(0, now()->diffInDays($this->grace_ends_at, false));
    }
}
