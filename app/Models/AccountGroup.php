<?php

namespace App\Models;

use App\Traits\HasDefaultPagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountGroup extends Model
{
    use HasFactory, Notifiable, HasUuids, HasDefaultPagination;

    protected $table = "account_groups";

    protected $fillable = [
        'title',
        'color'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    public function tenant(): HasMany
    {
        return $this->hasMany(Tenant::class, 'group_id');
    }

    public function getUsersCountAttribute(): int
    {
        return $this->tenant()->count();
    }
}
