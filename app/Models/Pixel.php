<?php

namespace App\Models;

use App\Enums\PixelType;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pixel extends Model
{
    use HasFactory, HasTenant;

    protected $table = 'pixels';

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'value'
    ];

    protected function casts(): array
    {
        return [
            'type' => PixelType::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class);
    }
}
