<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeature extends Model
{
    use HasFactory;

    protected $table = 'plan_features';

    protected $fillable = [
        'plan_id',
        'feature_id',
        'access_type',
        'limit_type',
        'limit_value',
        'limit_reset_period',
        'restore_on_delete',
    ];

    protected function casts(): array
    {
        return [
            'restore_on_delete' => 'boolean',
            'limit_value' => 'integer',
        ];
    }

    /**
     * Bu plan özelliğinin bağlı olduğu plan
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Bu plan özelliğinin bağlı olduğu özellik
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    /**
     * Özelliğin erişim tabanlı olup olmadığını kontrol eder
     */
    public function isAccessOnly(): bool
    {
        return $this->access_type === 'access_only';
    }

    /**
     * Özelliğin limit tabanlı olup olmadığını kontrol eder
     */
    public function isLimited(): bool
    {
        return $this->access_type === 'limited';
    }

    /**
     * Limitin sınırsız olup olmadığını kontrol eder
     */
    public function hasUnlimitedUsage(): bool
    {
        return $this->isLimited() && $this->limit_value === -1;
    }

    /**
     * Limitin yenilenebilir olup olmadığını kontrol eder
     */
    public function isRenewable(): bool
    {
        return $this->isLimited() && $this->limit_type === 'renewable';
    }

    /**
     * Limitin birikimli olup olmadığını kontrol eder
     */
    public function isCumulative(): bool
    {
        return $this->isLimited() && $this->limit_type === 'cumulative';
    }

    /**
     * Limit değerini formatlı şekilde döndürür
     */
    public function getFormattedLimitAttribute()
    {
        if ($this->isAccessOnly()) {
            return 'Erişim';
        }

        if ($this->hasUnlimitedUsage()) {
            return 'Sınırsız';
        }

        if ($this->isRenewable()) {
            $period = $this->getReadablePeriod();
            return "{$this->limit_value} / {$period}";
        }

        return $this->limit_value;
    }

    /**
     * Limit periyodunu okunabilir formatta döndürür
     */
    protected function getReadablePeriod()
    {
        switch ($this->limit_reset_period) {
            case 'hourly':
                return 'Saat';
            case 'daily':
                return 'Gün';
            case 'weekly':
                return 'Hafta';
            case 'monthly':
                return 'Ay';
            case 'yearly':
                return 'Yıl';
            default:
                return '';
        }
    }

    public function validate()
    {
        // Sınırlı erişimde limit türü gerekli
        if ($this->access_type === 'limited' && !$this->limit_type) {
            throw new \InvalidArgumentException('Sınırlı erişimde limit türü belirtilmelidir.');
        }

        // Sabit limit tipinde negatif değer kontrolü
        if ($this->access_type === 'limited' && $this->limit_type === 'cumulative' && $this->limit_value < 0) {
            throw new \InvalidArgumentException('Sabit limit için negatif değer girilemez.');
        }

        // Yenilenebilir limit tipinde -1 dışındaki negatif değerleri engelle
        if ($this->access_type === 'limited' && $this->limit_type === 'renewable' && $this->limit_value < -1) {
            throw new \InvalidArgumentException('Yenilenebilir limit için -1 (sınırsız) veya 0\'dan büyük değerler geçerlidir.');
        }
    }
}
