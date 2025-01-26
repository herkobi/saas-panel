<?php

namespace App\Repositories;

use App\Enums\AccountStatus;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TenantRepository extends BaseRepository
{
    protected $model = Tenant::class;

    public function getActiveTenants(): Collection
    {
        return $this->model::where('status', AccountStatus::ACTIVE)->get();
    }

    public function createTenant(array $data): Tenant
    {
        return $this->create([
            'name' => $data['name'],
            'domain' => $data['domain'] ?? null,
            'has_domain' => $data['has_domain'] ?? false,
            'status' => $data['status'],
            'settings' => $data['settings'] ?? [],
        ]);
    }

    public function updateTenant(string $id, array $data): Tenant
    {
        return $this->update($id, [
            'name' => $data['name'],
            'domain' => $data['domain'] ?? null,
            'has_domain' => $data['has_domain'] ?? false,
            'status' => $data['status'],
            'settings' => $data['settings'] ?? [],
        ]);
    }

    public function updateStatus(Tenant $tenant, AccountStatus $status): bool
    {
        return $tenant->update(['status' => $status]);
    }

    public function updateTenantPlan(string $id, array $data): Tenant
    {
        return $this->update($id, [
            'first_plan' => $data['first_plan'],
            'first_paid_plan' => $data['first_paid_plan'],
            'new_tenant' => $data['new_tenant'],
        ]);
    }

    public function createUserTenant(array $data): Tenant
    {
        return $this->create([
            'code' => Tenant::generateCode(),
            'domain' => $data['domain'] ?? null,
            'has_domain' => $data['has_domain'] ?? false,
            'status' => $data['status'] ?? AccountStatus::ACTIVE,
            'settings' => $data['settings'] ?? []
        ]);
    }
}
