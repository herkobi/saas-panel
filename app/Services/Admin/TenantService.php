<?php

namespace App\Services\Admin;

use App\Models\Tenant;
use App\Repositories\TenantRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TenantService
{
    protected $repository;

    public function __construct(TenantRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTenants(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function getActiveTenants(): Collection
    {
        return $this->repository->getActiveTenants();
    }

    public function getTenantById(string $id): Tenant
    {
        return $this->repository->getById($id);
    }

    public function createTenant(array $data): Tenant
    {
        return $this->repository->createTenant($data);
    }

    public function updateTenant(string $id, array $data): Tenant
    {
        return $this->repository->updateTenant($id, $data);
    }

    public function deleteTenant(string $id): void
    {
        $this->repository->delete($id);
    }
}
