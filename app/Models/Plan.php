<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LucasDotVin\Soulbscription\Models\Plan as ModelsPlan;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class Plan extends ModelsPlan
{
    use HasFactory;

    protected $table = "plans";

    protected $fillable = [
        'base',
        'name',
        'description',
        'periodicity',
        'periodicity_type',
        'price',
        'currency_id',
        'grace_days',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Periyot açıklamasını döndürür
     * Örnek: "1 Ay", "6 Ay", "1 Yıl" vb.
     */
    public function getPeriodicityTextAttribute(): string
    {
        if (!$this->periodicity || !$this->periodicity_type) {
            return 'Süresiz';
        }

        $period = match($this->periodicity_type) {
            PeriodicityType::Day => 'Gün',
            PeriodicityType::Week => 'Hafta',
            PeriodicityType::Month => 'Ay',
            PeriodicityType::Year => 'Yıl',
            default => $this->periodicity_type
        };

        return "{$this->periodicity} {$period}";
    }

    // Para birimi ile birlikte fiyat formatı
    public function getFormattedPriceAttribute()
    {
        if (!$this->price || !$this->currency) {
            return null;
        }

        return number_format($this->price, 2) . ' ' . $this->currency->symbol;
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'base');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
