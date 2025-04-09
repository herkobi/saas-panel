<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantStatusCheck
{
    use AuthUser;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // AuthUser trait'inden kullanıcı bilgisini al
        $this->initializeAuthUser();

        // Eğer tenant container'a bağlanmışsa ve kullanıcı giriş yapmışsa
        if (app()->bound('tenant') && $this->user) {
            // Get the tenant
            $tenant = app()->make('tenant');

            // Tenant varsa ve aktif değilse
            if ($tenant && !$tenant->status) {

                Activity::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $this->user->id,
                    'message' => 'tenant.status_check.disabled',
                    'log' => [
                        'action' => 'tenant_disabled_logout',
                        'tenant_name' => $tenant->name,
                        'user_email' => $this->user->email,
                        'user_type' => $this->user->type->value,
                    ],
                ]);

                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Hesabınız askıya alınmış veya devre dışı bırakılmıştır. Lütfen yönetici ile iletişime geçin.');
            }
        }

        return $next($request);
    }
}
