<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    use AuthUser;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // AuthUser trait'inden kullanıcı bilgisini al
        $this->initializeAuthUser();

        // Kullanıcı giriş yapmış ve tenant_id'si varsa
        if ($this->user && $this->user->tenant_id) {
            $tenant = Tenant::find($this->user->tenant_id);

            if ($tenant) {
                // Tenant'ı paylaşılan veride sakla
                app()->instance('tenant', $tenant);

                // Tenant ID'yi request'e ekle
                $request->merge(['tenant_id' => $tenant->id]);
            }
        }
        // Subdomain üzerinden tenant çözümleme (kullanıcı girişinden bağımsız)
        else if (config('tenant.subdomain_enabled', false) && $request->route('tenant')) {
            $tenant = Tenant::where('domain', $request->route('tenant'))->first();

            if ($tenant) {
                // Tenant'ı paylaşılan veride sakla
                app()->instance('tenant', $tenant);

                // Tenant ID'yi request'e ekle
                $request->merge(['tenant_id' => $tenant->id]);
            }
        }

        return $next($request);
    }
}
