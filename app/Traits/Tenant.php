<?php
namespace App\Traits;

trait Tenant
{
    public static function bootTenant()
    {
        static::creating(function ($model) {
            $model->tenant_id = auth()->user()->tenant_id;
        });

        static::retrieved(function ($model) {
            $model->where('tenant_id', auth()->user()->tenant_id);
        });
    }
}
