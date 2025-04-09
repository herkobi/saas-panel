<?php

use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'panel'])->group(function () {

    Route::controller(DashboardController::class)->group(function() {
        Route::get('/', 'index')->name('dashboard');
    });

    Route::controller(TenantController::class)->group(function() {
        Route::get('/tenants', 'index')->name('tenants.index');
        Route::get('/tenants/{tenant}', 'show')->name('tenants.show');
    });

    Route::controller(OrderController::class)->group(function() {
        Route::get('/orders', 'index')->name('orders.index');
    });

    Route::controller(PlanController::class)->group(function() {
        Route::get('/plans', 'index')->name('plans.index');
        Route::get('/plans/create', 'create')->name('plans.create');
        Route::post('/plans', 'store')->name('plans.store');
        Route::get('/plans/{plan}/edit', 'edit')->name('plans.edit');
        Route::put('/plans/{plan}', 'update')->name('plans.update');
        Route::delete('/plans/{plan}', 'destroy')->name('plans.destroy');
    });

    Route::controller(FeatureController::class)->group(function() {
        Route::get('/features', 'index')->name('features.index');
        Route::post('/features', 'store')->name('features.store');
        Route::get('/features/{feature}/edit', 'edit')->name('features.edit');
        Route::put('/features/{feature}', 'update')->name('features.update');
        Route::delete('/features/{feature}', 'destroy')->name('features.destroy');
    });

    Route::controller(CacheController::class)->group(function() {
        Route::get('/cache', 'index')->name('cache.management');
        Route::post('/cache/clear',  'clear')->name('cache.clear');
    });

    Route::controller(PageController::class)->group(function() {
        Route::get('/pages', 'index')->name('pages.index');
        Route::get('/pages/create', 'create')->name('pages.create');
        Route::post('/pages', 'store')->name('pages.store');
        Route::get('/pages/{page}/edit', 'edit')->name('pages.edit');
        Route::put('/pages/{page}', 'update')->name('pages.update');
        Route::delete('/pages/{page}', 'destroy')->name('pages.destroy');
    });

    Route::controller(ContractController::class)->group(function() {
        Route::get('/contracts', 'index')->name('contracts.index');
        Route::get('/contracts/create', 'create')->name('contracts.create');
        Route::post('/contracts', 'store')->name('contracts.store');
        Route::get('/contracts/{contract}/edit', 'edit')->name('contracts.edit');
        Route::put('/contracts/{contract}', 'update')->name('contracts.update');
        Route::delete('/contracts/{contract}', 'destroy')->name('contracts.destroy');
    });

    Route::redirect('settings', 'settings/profile');

    Route::controller(ProfileController::class)->group(function() {
        Route::get('settings/profile', 'edit')->name('profile.edit');
        Route::patch('settings/profile', 'update')->name('profile.update');
        Route::delete('settings/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(PasswordController::class)->group(function() {
        Route::get('settings/password', 'edit')->name('password.edit');
        Route::put('settings/password', 'update')->name('password.update');
    });

    Route::get('settings/appearance', function () {
        return Inertia::render('admin/settings/Appearance');
    })->name('appearance');
});
