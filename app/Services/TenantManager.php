<?php

namespace App\Services;

class TenantManager
{
    private $tenant;

    public function setTenant($tenant)
    {
        $this->tenant = $tenant;
        session()->put('tenant_id', $tenant->id);

        // Tenant specific configurations
        config([
            'app.name' => $tenant->name,
            'filesystems.disks.tenant.root' => storage_path("app/private/tenants/{$tenant->storage_folder}"),
        ]);
    }

    public function getTenant()
    {
        return $this->tenant;
    }
}
