<?php

namespace App\Models;

use App\Traits\HasDefaultPagination;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RealRashid\PlanCraft\Models\Plan;

class Feature extends Model
{
    use HasFactory, HasUuids, HasDefaultPagination, Sluggable;

    protected $table = "features";

    protected $fillable = [
        'title',
        'slug',
        'desc'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = $this->generateSlug($value);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'feature_plans', 'feature_id', 'plan_id');
    }
}
