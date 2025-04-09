<?php

namespace App\Models;

use App\Traits\Sluggable;
use App\Traits\HasTenant;
use App\Enums\CampaignStatus;
use App\Traits\TenantFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory, HasTenant, Sluggable, TenantFileUpload;

    protected $table = "campaigns";

    protected $fillable = [
        'tenant_id',
        'link_id',
        'title',
        'slug',
        'content',
        'terms',
        'external_link',
        'status',
        'is_published',
        'published_at',
        'is_featured',
        'start_date',
        'end_date',
        'apply_date',
        'apply_name',
        'image',
        'meta_title',
        'meta_description',
        'has_form',
        'form_id',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'has_form' => 'boolean',
            'views' => 'integer',
            'applications' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'apply_date' => 'datetime',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'status' => CampaignStatus::class,
        ];
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = $this->generateSlug($value);
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // public function form(): BelongsTo
    // {
    //     return $this->belongsTo(Form::class);
    // }
}
