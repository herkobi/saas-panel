<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    use AuthUser;

    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // AuthUser trait'inden kullanıcı bilgisini al
        $this->initializeAuthUser();

        // Kullanıcı tipine göre yönlendirme yapılıyor
        if ($this->user && $this->user->isAdmin()) {
            // Doğrudan URL ile yönlendirme yapalım, intended kullanmadan
            return redirect(route('panel.dashboard'));
        } elseif ($this->user && $this->user->isTenantUser()) {
            // Doğrudan URL ile yönlendirme yapalım, intended kullanmadan
            return redirect(route('app.dashboard'));
        }

        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
