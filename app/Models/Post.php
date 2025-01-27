<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDefaultPagination;
use App\Traits\HasFeatureConsumption;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, HasUuids, HasTenant, HasFeatureConsumption, HasDefaultPagination;

    protected $table = 'posts';

    protected $fillable = [
        'tenant_id',
        'title'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

}
