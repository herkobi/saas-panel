<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Enums\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Models\Admin\Admin as AdminModel;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $admin = AdminModel::where('email', $request->input_type)->orWhere('username', $request->input_type)->first();
        if ($admin && $admin->status == Admin::PASSIVE) {
            return Redirect::back()->with('error', __('admin/global.account.passive.text'));
        } else {
            $request->authenticate();
            $request->session()->regenerate();
            $admin->update([
                'user_agent' => $request->header('User-Agent'),
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp(),
            ]);

            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/panel/login');
    }
}
