<?php

namespace App\Models\Admin;

use App\Enums\Period;
use App\Enums\Status;
use LucasDotVin\Soulbscription\Models\Plan as MainPlan;

class Plan extends MainPlan
{

    protected $table = 'plans';

    protected $fillable = [
        'status',
        'title',
        'name',
        'price',
        'currency_id',
        'periodicity_type',
        'periodicity',
        'grace_days',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'status' => Status::class,
        'periodicity_type' => Period::class,
    ];

    /**
     * Selected Currency
     */
    public function currency() {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

}
