<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'description',
        'sort_order',
        'is_featured',
        'is_free',
        'billing_period',
        'country_code',
        'currency_code',
        'tax_rate_code',
        'monthly_price',
        'yearly_price',
        'trial_days',
        'grace_period_days',
        'payment_timing',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
            'status' => 'boolean',
            'monthly_price' => 'decimal:2',
            'yearly_price' => 'decimal:2',
            'sort_order' => 'integer',
            'trial_days' => 'integer',
            'grace_period_days' => 'integer',
        ];
    }

    /**
     * Planın sahip olduğu özellikleri alır
     */
    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    /**
     * Verilen özellik ID'sine göre plan özelliğini alır
     *
     * @param int $featureId
     * @return PlanFeature|null
     */
    public function getFeature($featureId): ?PlanFeature
    {
        return $this->planFeatures()->where('feature_id', $featureId)->first();
    }

    /**
     * Planın özelliklerini özellikleriyle birlikte alır
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function featuresWithDetails()
    {
        return $this->planFeatures()->with('feature')->get();
    }

    /**
     * Planın aktif olup olmadığını kontrol eder
     */
    public function isActive(): bool
    {
        return $this->status;
    }

    /**
     * Planın ücretsiz olup olmadığını kontrol eder
     */
    public function isFree(): bool
    {
        return $this->is_free;
    }

    /**
     * Aylık fiyatı formatlı şekilde döndürür
     */
    public function getFormattedMonthlyPriceAttribute()
    {
        if ($this->is_free) {
            return 'Ücretsiz';
        }

        return $this->formatPrice($this->monthly_price);
    }

    /**
     * Yıllık fiyatı formatlı şekilde döndürür
     */
    public function getFormattedYearlyPriceAttribute()
    {
        if ($this->is_free) {
            return 'Ücretsiz';
        }

        return $this->formatPrice($this->yearly_price);
    }

    /**
     * Fiyatı formatlar
     */
    protected function formatPrice($price)
    {
        if ($price === null) {
            return null;
        }

        // Config dosyasından para birimi bilgilerini al
        $currencies = config('tenant.currencies');
        $currency = collect($currencies)->firstWhere('iso_code', $this->currency_code);

        if (!$currency) {
            return $price;
        }

        $formatted = number_format(
            $price,
            $currency['decimals'],
            $currency['decimal_separator'],
            $currency['thousands_separator']
        );

        switch ($currency['position']) {
            case 'left':
                return $currency['symbol'] . $formatted;
            case 'left_space':
                return $currency['symbol'] . ' ' . $formatted;
            case 'right':
                return $formatted . $currency['symbol'];
            case 'right_space':
                return $formatted . ' ' . $currency['symbol'];
            default:
                return $formatted;
        }
    }

    public function getFormattedPrice($price)
    {
        return $this->formatPrice($price);
    }
}
