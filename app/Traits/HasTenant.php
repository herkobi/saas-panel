<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait HasTenant
{
    protected static function bootHasTenant(): void
    {
        static::creating(function ($model) {
            // Eğer tenant_id zaten set edilmişse, onu koruyalım
            if (!$model->isDirty('tenant_id')) {
                if (session()->has('tenant_id')) {
                    $model->tenant_id = session()->get('tenant_id');
                }
                elseif (Auth::check() && Auth::user()->tenant_id) {
                    $model->tenant_id = Auth::user()->tenant_id;
                }
            }
        });

        // Update işlemi için de aynı mantık
        static::updating(function ($model) {
            if (!$model->isDirty('tenant_id')) {
                if (session()->has('tenant_id')) {
                    $model->tenant_id = session()->get('tenant_id');
                }
                elseif (Auth::check() && Auth::user()->tenant_id) {
                    $model->tenant_id = Auth::user()->tenant_id;
                }
            }
        });

        static::addGlobalScope(new TenantScope);
    }

    // Tenant ilişkisi - Bu kesinlikle gerekli
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
