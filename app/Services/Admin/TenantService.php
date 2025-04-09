<?php

namespace App\Services\Admin;

use App\Enums\UserType;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TenantService
{
    /**
     * Tüm tenant'ları sahipleriyle birlikte getir
     */
    public function getAllTenants(): Collection
    {
        return Tenant::with('users')
            ->whereHas('users', function ($query) {
                $query->where('type', UserType::TENANT_OWNER->value);
            })
            ->latest()
            ->get();
    }

    /**
     * Tenant sahibini getir
     */
    public function getTenantOwner(Tenant $tenant): ?User
    {
        return $tenant->users()->where('type', UserType::TENANT_OWNER->value)->first();
    }

    /**
     * Tenant kullanıcılarını getir
     */
    public function getTenantUsers(Tenant $tenant): Collection
    {
        return $tenant->users;
    }
}
