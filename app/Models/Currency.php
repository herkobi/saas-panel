<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Sluggable;
use App\Enums\Status;
use App\Traits\HasDefaultPagination;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory, HasUuids, Sluggable, HasDefaultPagination;

    protected $table = "currencies";

    protected $fillable = [
        'status',
        'name',
        'symbol',
        'symbol_position',
        'thousands_separator',
        'decimal_separator',
        'decimal_digits',
        'iso_code'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'status' => Status::class,
        ];
    }

    public function bacs(): HasMany
    {
        return $this->hasMany(Bacs::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }
}
