<?php

namespace App\Http\Middleware;

use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PanelAccess
{
    use AuthUser;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // AuthUser trait'inden kullanıcı bilgisini al
        $this->initializeAuthUser();

        // Kullanıcı girişi yapılmış mı ve admin mi kontrolü
        if (!$this->user || !$this->user->isAdmin()) {
            if (!$this->user) {
                return redirect()->route('login');
            }

            // Kullanıcı giriş yapmış ama admin değilse, çıkış yaptırıp login'e yönlendir
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Bu alana erişim yetkiniz bulunmamaktadır. Lütfen yönetici hesabı ile giriş yapın.');
        }

        return $next($request);
    }
}
