<?php

namespace App\Http\Middleware;

use App\Enums\AccountStatus;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TenantStatusCheck
{
    use AuthUser;

    public function __construct()
    {
        $this->initializeAuthUser();
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $this->user;
        $tenant = $user->tenant;

        // Tenant yoksa veya yönetici ise geçir
        if (!$tenant || $request->routeIs('panel.*')) {
            return $next($request);
        }

        // Account Status kontrolü
        if ($tenant->status === AccountStatus::DRAFT || $tenant->status === AccountStatus::PASSIVE) {
            if ($request->isMethod('post') ||
                $request->isMethod('put') ||
                $request->isMethod('patch') ||
                $request->isMethod('delete')
            ) {
                return redirect()->back()
                    ->withErrors(['error' => 'Hesap durumunuz nedeniyle bu işlemi gerçekleştiremezsiniz.']);
            }
        }

        return $next($request);
    }
}
