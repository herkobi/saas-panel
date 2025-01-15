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
}
