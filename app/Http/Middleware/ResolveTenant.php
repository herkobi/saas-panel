<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use App\Services\TenantManager;

class ResolveTenant
{
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    public function handle($request, Closure $next)
    {
        if (config('tenant.use_subdomain')) {
            $hostname = $request->getHost();
            $tenant = Tenant::where('domain', $hostname)->first();

            if (!$tenant) {
                abort(404, 'Tenant not found');
            }

            $this->tenantManager->setTenant($tenant);
        }

        return $next($request);
    }
}
