<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Responses\LoginResponse;
use App\Models\Agreement;
use App\Services\Admin\Plan\PlanService;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function (Request $request) {
            if (!$request->has('plan')) {
                return redirect()->route('front'); // veya planların listelendiği sayfa
            }

            $planService = app(PlanService::class);
            $plan = $planService->getPlanById($request->plan);

            if (!$plan) {
                return redirect()->route('front')
                    ->with('error', 'Geçersiz plan seçimi.');
            }

            // Planı session'a kaydet
            session(['selected_plan' => $plan->id]);

            // Sözleşmeler
            $registerAgreements = Agreement::where('user_type', UserType::USER)
                ->where('show_on_register', true)
                ->where('status', Status::ACTIVE)
                ->get();

            return view('auth.register', [
                'registerAgreements' => $registerAgreements,
                'plan' => $plan
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.password.forgot');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.password.reset', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('auth.email-verify');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.password.confirm');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, LoginResponse::class);
    }
}
