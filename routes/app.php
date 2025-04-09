<?php

use App\Http\Controllers\Tenant\CampaignController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\DomainController;
use App\Http\Controllers\Tenant\LinkController;
use App\Http\Controllers\Tenant\PixelController;
use App\Http\Controllers\Tenant\SpaceController;
use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Tenant\PasswordController;
use App\Http\Controllers\Tenant\StatController;
use App\Http\Controllers\Tenant\SubscriptionController;
use App\Http\Controllers\Tenant\TemplateController;
use App\Http\Controllers\Tenant\TenantUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'resolve.tenant', 'tenant.status', 'check.user.status'])->group(function () {

    Route::controller(DashboardController::class)->group(function() {
        Route::get('/', 'index')->name('dashboard');
    });

    Route::controller(LinkController::class)->group(function() {
        Route::get('/links', 'index')->name('links');
        Route::get('/link/{link}/edit', 'edit')->name('link.edit');
        Route::get('/link/{link}/detail', 'show')->name('link.detail');
        Route::post('/link/{link}/update', 'update')->name('link.update');
        Route::delete('/link/{link}/delete', 'destroy')->name('link.destroy');

        // Ek işlemler için route'lar
        Route::post('/link/{link}/toggle', 'toggleStatus')->name('link.toggle');
        Route::post('/link/{link}/update/basic', 'updateBasicInfo')->name('link.update.basic');
        Route::post('/link/{link}/update/extra', 'updateExtraInfo')->name('link.update.extra');
        Route::post('/link/{link}/update/utm', 'updateUtmInfo')->name('link.update.utm');
        Route::post('/link/{link}/update/target', 'updateTargetInfo')->name('link.update.target');
    });

    Route::controller(StatController::class)->group(function() {
        Route::get('/link/{id}/stats', 'detail')->name('link.stats');
        Route::get('/link/{id}/stats/export', 'export')->name('link.stats.export');
    });

    Route::controller(PixelController::class)->group(function() {
        Route::get('/pixels', 'index')->name('pixels');
        Route::get('/pixel/{pixel}/edit', 'edit')->name('pixel.edit');
        Route::post('/pixel/{pixel}/update', 'update')->name('pixel.update');
        Route::delete('/pixel/{pixel}/delete', 'destroy')->name('pixel.destroy');
    });

    Route::controller(SpaceController::class)->group(function() {
        Route::get('/spaces', 'index')->name('spaces');
        Route::get('/space/{space}/edit', 'edit')->name('space.edit');
        Route::post('/space/{space}/update', 'update')->name('space.update');
        Route::delete('/space/{space}/delete', 'destroy')->name('space.destroy');
    });

    Route::controller(CampaignController::class)->group(function() {
        Route::get('/campaigns', 'index')->name('campaigns');
        Route::get('/campaign/{campaign}/detail', 'show')->name('campaign.detail');
        Route::get('/campaign/{campaign}/edit', 'edit')->name('campaign.edit');
        Route::post('/campaign/{campaign}/update', 'update')->name('campaign.update');
        Route::delete('/campaign/{campaign}/delete', 'destroy')->name('campaign.destroy');
    });

    Route::controller(TemplateController::class)->group(function() {
        Route::get('/template', 'index')->name('template');
    });

    Route::controller(DomainController::class)->group(function() {
        Route::get('/domain', 'index')->name('domain');
    });

    Route::redirect('/settings', 'settings/profile');

    Route::controller(ProfileController::class)->group(function() {
        Route::get('/settings/profile', 'edit')->name('profile.edit');
        Route::patch('/settings/profile', 'update')->name('profile.update');
        Route::delete('/settings/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(PasswordController::class)->group(function() {
        Route::get('/settings/password', 'edit')->name('password.edit');
        Route::put('/settings/password', 'update')->name('password.update');
    });

    Route::get('/settings/appearance', function () {
        return Inertia::render('tenant/settings/Appearance');
    })->name('appearance');

    // Abonelik İşlemleri
    Route::controller(SubscriptionController::class)->group(function() {
        Route::get('/subscription/plans', 'plans')->name('subscription.plans');
        Route::get('/subscription/billing', 'billing')->name('subscription.billing');
        Route::get('/subscription/usage', 'usage')->name('subscription.usage');
        Route::get('/subscription/checkout/{plan}', 'checkout')->name('subscription.checkout');
        Route::post('/subscription/process/{plan}', 'process')->name('subscription.process');
        Route::get('/subscription/success', 'success')->name('subscription.success');
        Route::post('/subscription/{subscription}/cancel', 'cancel')->name('subscription.cancel');

        // Yeni rotalar
        Route::get('/subscription/change', 'changePlan')->name('subscription.change');
        Route::get('/subscription/change/{plan}/options', 'changeOptions')->name('subscription.change.options');
        Route::post('/subscription/change/{plan}/process', 'processChange')->name('subscription.change.process');

        // İptal işlemleri
        Route::get('/subscription/{subscription}/cancel', 'cancelOptions')->name('subscription.cancel.options');
        Route::post('/subscription/{subscription}/cancel', 'cancel')->name('subscription.cancel');

    });

    Route::controller(TenantUserController::class)->group(function() {
        Route::get('/subscription/users', 'index')->name('subscription.users.index');
        Route::get('/subscription/users/create', 'create')->name('subscription.users.create');
        Route::post('/subscription/users', 'store')->name('subscription.users.store');
        Route::get('/subscription/users/{user}/edit', 'edit')->name('subscription.users.edit');
        Route::post('/subscription/users/{user}', 'update')->name('subscription.users.update');
        Route::post('/subscription/users/{user}/activate', 'activate')->name('subscription.users.activate');
        Route::post('/subscription/users/{user}/deactivate', 'deactivate')->name('subscription.users.deactivate');
        Route::get('/subscription/users/invite', 'invite')->name('subscription.users.invite');
        Route::post('/subscription/invite/user', 'sendInvite')->name('subscription.invite.user');
    });

    // Abonelik gerektiren create ve store metodları
    Route::middleware('check.subscription')->group(function () {
        // Link create/store
        Route::controller(LinkController::class)->group(function() {
            Route::get('/link/create', 'create')->name('link.create');
            Route::post('/link/store', 'store')->name('link.store');
        });

        // Pixel create/store
        Route::controller(PixelController::class)->group(function() {
            Route::get('/pixel/create', 'create')->name('pixel.create');
            Route::post('/pixel/store', 'store')->name('pixel.store');
        });

        // Space create/store
        Route::controller(SpaceController::class)->group(function() {
            Route::get('/space/create', 'create')->name('space.create');
            Route::post('/space/store', 'store')->name('space.store');
        });

        // Campaign create/store
        Route::controller(CampaignController::class)->group(function() {
            Route::get('/campaign/create', 'create')->name('campaign.create');
            Route::post('/campaign/store', 'store')->name('campaign.store');
        });
    });

    Route::get('/demo', function () {
        return Inertia::render('tenant/Demo');
    })->name('demo');
});
