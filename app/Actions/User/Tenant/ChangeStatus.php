<?php

namespace App\Actions\User\Tenant;

use App\Models\Tenant;
use App\Events\User\Tenant\StatusChanged as Event;
use App\Services\User\TenantService;
use App\Enums\AccountStatus;
use App\Traits\AuthUser;

class ChangeStatus
{
    use AuthUser;

    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
        $this->initializeAuthUser();
    }

    public function execute(Tenant $tenant, AccountStatus $newStatus): bool
    {
        $oldStatus = $tenant->status;

        $updated = $this->tenantService->changeStatus($tenant, $newStatus);

        if ($updated) {
            event(new Event($tenant, $oldStatus, $newStatus, $this->user));
        }

        return $updated;
    }
}
