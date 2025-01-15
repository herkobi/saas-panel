<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Subdomain kontrolü
            if (config('tenant.use_subdomain', false)) {
                Route::domain('{tenant}.' . config('app.domain'))
                    ->middleware(['web', 'resolve.tenant'])
                    ->group(base_path('routes/app.php'));
            } else {
                Route::middleware('web')
                    ->group(base_path('routes/app.php'));
            }

            // Panel route'ları değişmiyor
            Route::middleware('web')
                ->group(base_path('routes/panel.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'panel' => \App\Http\Middleware\PanelAccess::class,
            'resolve.tenant' => \App\Http\Middleware\ResolveTenant::class,
            'check.tenant' => \App\Http\Middleware\CheckTenant::class,
            'tenant.status' => \App\Http\Middleware\TenantStatusCheck::class,
            'subscription.check' => \App\Http\Middleware\SubscriptionCheck::class,
            'userstatus' => \App\Http\Middleware\UserStatusCheck::class,
            'system.settings' => \App\Http\Middleware\SystemSettings::class,
            'agreement.check' => \App\Http\Middleware\AgreementCheck::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
