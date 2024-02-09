<?php

namespace App\Models\Admin;

use App\Enums\Period;
use App\Enums\Status;
use LucasDotVin\Soulbscription\Models\Feature as MainFeature;

class Feature extends MainFeature
{
    protected $table = 'features';

    protected $fillable = [
        'status',
        'title',
        'name',
        'consumable',
        'quota',
        'postpaid',
        'periodicity',
        'periodicity_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => Status::class,
        'periodicity_type' => Period::class,
    ];

}
