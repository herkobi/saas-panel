<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\Account\AccountController;
use App\Http\Controllers\User\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'auth.session', 'verified', 'userpanel', 'accountstatus'])->prefix('app')->name('app.')->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/passive', 'passive')->name('passive');
    });

    Route::controller(AccountController::class)->group( function() {
        Route::get('/account/plans', 'index')->name('account.plans');
        Route::get('/account/payments', 'payments')->name('account.payments');
        Route::get('/account/invoices', 'invoices')->name('account.invoices');

        Route::get('/account/invoice-detail', 'invoicedetail')->name('account.invoicedetail');
        Route::get('/account/invoice-detail/edit/{detail}', 'editInvoiceDetail')->name('account.invoicedetail.edit');
        Route::post('/account/invoice-detail/update/{detail}', 'updateInvoiceDetail')->name('account.invoicedetail.update');
        Route::get('/account/invoice-detail/create', 'newInvoiceDetail')->name('account.invoicedetail.create');
        Route::post('/account/invoice-detail/store', 'storeInvoiceDetail')->name('account.invoicedetail.store');
        Route::delete('/account/invoice-detail/delete/{detail}', 'deleteInvoiceDetail')->name('account.invoicedetail.delete');
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
