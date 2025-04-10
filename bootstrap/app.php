<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Tenant routes (app paneli için)
            if (config('tenant.subdomain_enabled', false)) {
                Route::domain('{tenant}.' . config('tenant.domain.suffix', 'localhost'))
                    ->middleware(['web', 'resolve.tenant'])
                    ->name('app.')
                    ->group(base_path('routes/app.php'));
            } else {
                Route::prefix( 'app')
                    ->middleware(['web', 'resolve.tenant'])
                    ->name('app.')
                    ->group(base_path('routes/app.php'));
            }

            // Admin panel route'ları
            Route::prefix('panel')
                ->middleware(['web'])
                ->name('panel.')
                ->group(base_path('routes/panel.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'check.subscription' => \App\Http\Middleware\CheckSubscription::class,
            'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
            'resolve.tenant' => \App\Http\Middleware\ResolveTenant::class,
            'tenant.status' => \App\Http\Middleware\TenantStatusCheck::class,
            'panel' => \App\Http\Middleware\PanelAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
