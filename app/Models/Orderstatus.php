<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Status;
use App\Traits\HasDefaultPagination;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orderstatus extends Model
{
    use HasFactory, HasUuids, HasDefaultPagination;

    protected $table = "orderstatuses";

    protected $fillable = [
        'status',
        'code',
        'title',
        'description'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'status' => Status::class,
        ];
    }

}
