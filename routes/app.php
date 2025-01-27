<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\Account\Account\AccountController;
use App\Http\Controllers\User\Account\Payments\PaymentsController;
use App\Http\Controllers\User\Account\Plans\PlansController;
use App\Http\Controllers\User\Account\Profile\ProfileController;
use App\Http\Controllers\User\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'auth.session', 'verified', 'resolve.tenant', 'check.tenant', 'panel:user', 'system.settings', 'userstatus', 'tenant.status', 'subscription.check'])->prefix('app')->name('app.')->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/passive', 'passive')->name('passive');
        Route::get('/sign', 'sign')->name('agreement.sign');
        Route::post('/accept-agreement', 'accept')->name('agreement.accept');
        Route::get('/states', 'getStates')->name('country.states');
        Route::get('/taxes', 'getTaxes')->name('taxes');
    });

    Route::controller(PostController::class)->middleware('check.feature:icerik-yonetimi')->group(function() {
        Route::get('/posts', 'index')->name('posts');
        Route::get('/post/create', 'create')->name('post.create');
        Route::post('/post/store', 'store')->name('post.store');
        Route::get('/post/edit/{post}', 'edit')->name('post.edit');
        Route::post('/post/update/{post}', 'update')->name('post.update');
        Route::delete('/post/delete/{post}', 'destroy')->name('post.delete');
    });

    Route::controller(AccountController::class)->group( function() {
        Route::get('/account', 'index')->name('account');
        Route::post('/account/update', 'update')->name('account.update');
        Route::post('/account/update-ajax', 'updateAjax')->name('account.updateAjax');
    });

    Route::controller(PlansController::class)->group( function() {
        Route::get('/account/plans', 'index')->name('account.plans');
    });

    Route::controller(PaymentsController::class)->group( function() {
        Route::get('/account/payments', 'index')->name('account.payments');
        Route::get('/account/payment/create/plan/{plan}', 'create')->name('account.payment.create');
        Route::post('/account/payment/store', 'store')->name('account.payment.store');
        Route::get('/account/payment/{code}', 'show')->name('account.payment.show');

        Route::get('/account/payment/free-success/{code}', 'freeSuccess')->name('account.payment.free-success');
        Route::get('/account/payment/bacs-success/{code}', 'bacsSuccess')->name('account.payment.bacs-success');
        Route::post('/account/payment/{code}/upload', 'uploadDocument')->name('account.payment.upload');

        Route::get('/account/payment/blocked', 'normaluser')->name('account.payment.normaluser');
    });

    Route::controller(ProfileController::class)->group( function() {
        Route::get('/profile', 'index')->name('profile');
        Route::post('/profile/update/{user}', 'update')->name('profile.update');
        Route::post('/profile/email-update/{user}', 'mailUpdate')->name('profile.email.update');
        Route::post('/profile/password-update/{user}', 'passwordUpdate')->name('profile.password.update');

        Route::get('/profile/activity-logs', 'activitylogs')->name('profile.activitylogs');
        Route::get('/profile/auth-logs', 'authlogs')->name('profile.authlogs');

        Route::get('/profile/2fa', 'twofactor')->name('profile.twofactor');
    });
});
