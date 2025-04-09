<?php

use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\ContentController;
use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Tenant\PasswordController;
use App\Http\Controllers\Tenant\SubscriptionController;
use App\Http\Controllers\Tenant\TenantUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'resolve.tenant', 'tenant.status', 'check.user.status'])->group(function () {

    Route::controller(DashboardController::class)->group(function() {
        Route::get('/', 'index')->name('dashboard');
    });

    Route::controller(ContentController::class)->group(function() {
        Route::get('/content', 'index')->name('content');
        Route::get('/content/{content}/edit', 'edit')->name('content.edit');
        Route::post('/content/{content}/update', 'update')->name('content.update');
        Route::delete('/content/{content}/delete', 'destroy')->name('content.destroy');
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
        // Content create/store
        Route::controller(ContentController::class)->group(function() {
            Route::get('/content/create', 'create')->name('content.create');
            Route::post('/content/store', 'store')->name('content.store');
        });
    });

    Route::get('/demo', function () {
        return Inertia::render('tenant/Demo');
    })->name('demo');
});
