<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CheckTenant
{
    public function handle(Request $request, Closure $next)
    {
        // Config'den subdomain desteğinin aktif olup olmadığını kontrol et
        if (config('tenant.use_subdomain', false)) {
            $host = $request->getHost();
            $parts = explode('.', $host);

            // Subdomain varsa
            if (count($parts) > 2) {
                $subdomain = $parts[0];

                // Subdomain'e göre tenant'ı bul
                $tenant = Tenant::where('domain', $subdomain)
                    ->where('has_domain', true)
                    ->first();

                if ($tenant) {
                    // Tenant'ı request'e ekle
                    $request->merge(['tenant' => $tenant]);
                    return $next($request);
                }
            }
        }

        // Subdomain yoksa veya devre dışıysa, authentication üzerinden tenant'ı bul
        if (Auth::check()) {
            $tenant = Auth::user()->tenant;
            if ($tenant) {
                $request->merge(['tenant' => $tenant]);
            }
        }

        return $next($request);
    }
}
