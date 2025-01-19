<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Status;
use App\Traits\HasDefaultPagination;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // Order ile ilişki
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'orderstatus_id');
    }

    // Eğer ihtiyaç olursa belirli bir statusteki orderları çekmek için scope
    public function scopeWithOrders($query)
    {
        return $query->with('orders');
    }

}
