<?php

namespace App\Http\Middleware;

use App\Enums\AccountStatus;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        switch ($tenant->status) {
            case AccountStatus::DRAFT:
                if ($user->isTenantOwner()) {
                    return redirect()->route('app.home')->with('error', 'Sistemi kullanabilmek için ödeme yapmanız gerekmektedir.');
                } else {
                    return redirect()->route('app.home')->with('error', 'Hesabınız geçici olarak devre dışı bırakılmıştır. Lütfen tenant yöneticiniz ile iletişime geçin.');
                }

            case AccountStatus::PASSIVE:
                if (!$request->isMethod('get')) {
                    return redirect()->route('app.home')
                        ->withErrors(['error' => 'Hesabınız pasif durumda. Lütfen hesap durumunuzu kontrol edin.']);
                }
                break;

            case AccountStatus::DELETED:
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['error' => 'Hesabınız silinmiş durumda.']);
        }

        return $next($request);
    }
}
