<?php

namespace App\Services\User;

use App\Models\Tenant;
use App\Repositories\TenantRepository;
use App\Enums\AccountStatus;

class TenantService
{
    protected $repository;

    public function __construct(TenantRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createForUser(array $data): Tenant
    {
        return $this->repository->createUserTenant([
            'domain' => $data['domain'] ?? null,
            'has_domain' => config('tenant.use_subdomain') && !empty($data['domain']),
            'status' => AccountStatus::ACTIVE
        ]);
    }

    public function changeStatus(Tenant $tenant, AccountStatus $status): bool
    {
        return $this->repository->updateStatus($tenant, $status);
    }
}
