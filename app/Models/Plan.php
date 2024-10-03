<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\Plan as ModelsPlan;

class Plan extends ModelsPlan
{
    use HasFactory;

    protected $table = "plans";

    protected $fillable = [
        'name',
        'description',
        'periodicity_type',
        'periodicity',
        'price',
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
}
