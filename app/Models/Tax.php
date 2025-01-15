<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Status;
use App\Traits\HasDefaultPagination;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tax extends Model
{
    use HasFactory, HasUuids, HasDefaultPagination;

    protected $table = "taxes";

    protected $fillable = [
        'status',
        'title',
        'code',
        'value'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'status' => Status::class,
        ];
    }

    public function regions(): HasMany
    {
        return $this->hasMany(TaxRegion::class);
    }
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'tax_regions')->distinct();
    }
    public function states(): BelongsToMany
    {
        return $this->belongsToMany(State::class, 'tax_regions')->distinct();
    }

    public function getEffectiveRate(?string $stateId = null): float
    {
        // Eyalet belirtilmişse eyalete özel oran var mı diye bak
        if ($stateId) {
            $region = $this->regions()
                ->where('state_id', $stateId)
                ->first();

            if ($region) {
                return (float)$this->value;
            }
        }

        // Eyalete özel yoksa ülke geneli oranı dön
        $region = $this->regions()
            ->whereNull('state_id')
            ->first();

        return $region ? (float)$this->value : 0;
    }

    public function isApplicableToRegion(string $countryId, ?string $stateId = null): bool
    {
        return $this->regions()
            ->where('country_id', $countryId)
            ->when($stateId, function($query) use ($stateId) {
                $query->where('state_id', $stateId);
            })
            ->exists();
    }
}
