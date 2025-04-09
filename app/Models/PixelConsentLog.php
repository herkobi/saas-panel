<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PixelConsentLog extends Model
{
    use HasFactory, HasTenant;

    protected $table = 'pixel_consent_logs';

    protected $fillable = [
        'tenant_id',
        'link_id',
        'ip_address',
        'user_agent',
        'consent_status',
        'referer',
        'consent_timestamp'
    ];

    protected $casts = [
        'consent_status' => 'boolean',
        'consent_timestamp' => 'datetime',
    ];

    /**
     * Link ilişkisi
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    /**
     * Tenant ilişkisi
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
