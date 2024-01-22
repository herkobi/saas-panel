<?php
namespace App\Traits;

use App\Scopes\GlobalQuery;

trait TenantQuery
{
    public static function bootTenantScoped()
    {
        static::addGlobalScope(new GlobalQuery);
    }
}
