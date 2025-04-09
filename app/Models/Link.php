<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    use HasFactory, HasTenant;

    protected $table = 'links';

    protected $fillable = [
        'tenant_id',
        'alias',
        'url',
        'title',
        'description',
        'image',
        'target_type',
        'country_target',
        'platform_target',
        'language_target',
        'rotation_target',
        'last_rotation',
        'goal',
        'password',
        'password_hint',
        'disabled',
        'expiration_url',
        'expiration_clicks',
        'clicks',
        'space_id',
        'ends_at',
        'published_at'
    ];

    protected function casts(): array
    {
        return [
            'target_type' => 'integer',
            'country_target' => 'array',
            'platform_target' => 'array',
            'language_target' => 'array',
            'rotation_target' => 'array',
            'last_rotation' => 'integer',
            'disabled' => 'integer',
            'clicks' => 'integer',
            'expiration_clicks' => 'integer',
            'goal' => 'integer',
            'ends_at' => 'datetime',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function pixels(): BelongsToMany
    {
        return $this->belongsToMany(Pixel::class);
    }

    public function stats(): HasMany
    {
        return $this->hasMany(Stat::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
