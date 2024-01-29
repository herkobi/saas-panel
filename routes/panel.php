<?php

use App\Http\Controllers\Admin\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordController,
    PasswordResetLinkController,
    VerifyEmailController
};
use App\Http\Controllers\Admin\Accounts\{
    AccountController,
};
use App\Http\Controllers\Admin\Gateways\{
    GatewayController,
    BacController,
    PaytrController,
};
use App\Http\Controllers\Admin\Users\{
    UserController,
};
use App\Http\Controllers\Admin\Settings\{
    CurrencyController,
    LocationController,
    PageController,
    PaymentController,
    SettingController,
};
use App\Http\Controllers\Admin\Profile\{
    ProfileController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/panel', '/panel/login');

Route::middleware(['guest:admin'])->prefix('panel')->name('panel.')->group(function () {

    Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::get('/login', 'create')->name('login');
        Route::post('/login', 'store')->name('login.store');
    });

    Route::controller(PasswordResetLinkController::class)->group(function () {
        Route::get('/forgot-password', 'create')->name('forgot.password');
        Route::post('/forgot-password', 'store')->name('forgot.password.store');
    });

    Route::controller(NewPasswordController::class)->group(function () {
        Route::get('/reset-password/{token}', 'create')->name('password.reset');
        Route::post('/reset-password', 'store')->name('password.reset.store');
    });

});

Route::middleware(['auth:admin'])->prefix('panel')->name('panel.')->group(function () {

    Route::get('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    Route::middleware(['signed', 'throttle:6,1'])->group(function () {
        Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->name('verification.verify');
    });

    Route::middleware('throttle:6,1')->controller(EmailVerificationNotificationController::class)->group(function () {
        Route::post('/email/verification-notification', 'store')->name('verification.send');
    });

    /**
     * Oturumu Kapat
     */
    Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::post('/logout', 'destroy')->name('logout');
    });
});

Route::middleware(['auth:admin', 'auth.session', 'admin.verified'])->prefix('panel')->name('panel.')->group(function () {

    /**
     * Başlangıç
     */
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    /**
     * Hesap Yönetimi
     */
    Route::controller(AccountController::class)->group(function() {

        /**
         * Hesaplar
         */
        Route::get('/accounts', 'index')->name('accounts');
        Route::get('/account/detail/plan/{user}', 'plan')->name('account.plan.detail');
        Route::get('/account/detail/order/{user}', 'order')->name('account.order.detail');
        Route::get('/account/detail/activity/{user}', 'activity')->name('account.activity.detail');

    });

    /**
     * Kullanıcı İşlemleri / Yönetimi
     */
    Route::prefix('users')->name('users.')->group(function () {
        /**
         * Sistem Kullanıcıları
         */
        Route::controller(UserController::class)->group(function(){
            Route::get('/list', 'index')->name('list');
            Route::get('/create', 'create')->name('create');
            Route::post('/create/store', 'store')->name('create.store');
            Route::get('/edit/{admin}', 'edit')->name('edit');
            Route::post('/edit/update/{admin}', 'update')->name('edit.update');
            Route::get('/detail/{admin}', 'detail')->name('detail');
            Route::get('/detail/auth/{admin}', 'auth')->name('auth.detail');
        });

    });

    /**
     * Ödeme Yöntemleri
     */
    Route::prefix('gateways')->name('gateways.')->group(function() {

        /**
         * Ödeme Yöntemleri
         */
        Route::controller(GatewayController::class)->group(function(){
            Route::get('/bac/list', 'bac')->name('bac');
            Route::get('/cc/list', 'cc')->name('cc');
        });

        /**
         * Eft/Havale İle Ödeme
         */
        Route::controller(BacController::class)->group(function(){
            Route::get('/bac/edit/{bac}', 'edit')->name('bac.edit');
            Route::post('/bac/edit/update/{bac}', 'update')->name('bac.update');
            Route::get('/bac/create', 'create')->name('bac.create');
            Route::post('/bac/create/store', 'store')->name('bac.create.store');
            Route::post('/bac/destroy/{bac}', 'destroy')->name('bac.destroy');
        });

        /**
         * PayTR İle Ödeme
         */
        Route::controller(PaytrController::class)->group(function(){
            Route::get('/paytr/edit/{paytr}', 'edit')->name('paytr.edit');
            Route::post('/paytr/edit/update/{paytr}', 'update')->name('paytr.update');
            Route::get('/paytr/create', 'create')->name('paytr.create');
            Route::post('/paytr/create/store', 'store')->name('paytr.create.store');
            Route::post('/paytr/destroy/{paytr}', 'destroy')->name('paytr.destroy');
        });
    });

    /**
     * Ayarlar
     */
    Route::prefix('settings')->name('settings.')->group(function () {
        /**
         * Genel Ayarlar
         */
        Route::controller(SettingController::class)->group(function(){
            Route::get('/general', 'index')->name('general');
            Route::post('/general/update', 'update')->name('general.update');
        });

        /**
         * Bölgeler
         */
        Route::controller(LocationController::class)->group(function(){
            Route::get('/locations', 'index')->name('locations');
            Route::get('/location/create', 'create')->name('location.create');
            Route::post('/location/create/store', 'store')->name('location.create.store');
            Route::get('/location/edit/{location}', 'edit')->name('location.edit');
            Route::post('/location/update/{location}', 'update')->name('location.update');
        });

        /**
         * Para Birimleri
         */
        Route::controller(CurrencyController::class)->group(function(){
            Route::get('/currencies', 'index')->name('currencies');
            Route::get('/currency/create', 'create')->name('currency.create');
            Route::post('/currency/create/store', 'store')->name('currency.create.store');
            Route::get('/currency/edit/{currency}', 'edit')->name('currency.edit');
            Route::post('/currency/update/{currency}', 'update')->name('currency.update');
            Route::post('/currency/destroy/{currency}', 'destroy')->name('currency.destroy');
        });

        /**
         * Ödeme Sistemleri
         */
        Route::controller(PaymentController::class)->group(function(){
            Route::get('/payments', 'index')->name('payments');
            Route::get('/payment/edit/{payment}', 'edit')->name('payment.edit');
            Route::post('/payment/update/{payment}', 'update')->name('payment.update');
            Route::get('/payment/create', 'create')->name('payment.create');
            Route::post('/payment/create/store', 'store')->name('payment.create.store');
            Route::post('/payment/destroy/{payment}', 'destroy')->name('payment.destroy');
        });

        /**
         * Sözleşmeler
         */
        Route::controller(PageController::class)->group(function(){
            Route::get('/pages', 'index')->name('pages');
            Route::get('/page/create', 'create')->name('page.create');
            Route::post('/page/create/store', 'store')->name('page.create.store');
            Route::get('/page/edit/{page}', 'edit')->name('page.edit');
            Route::post('/page/update/{page}', 'update')->name('page.update');
            Route::post('/page/destroy/{page}', 'destroy')->name('page.destroy');
        });

    });

    /**
     * Profil Yönetimi
     */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    /**
     * Şifre İşlemleri
     */
    Route::controller(ConfirmablePasswordController::class)->group(function () {
        Route::get('/confirm-password', 'show')->name('password.confirm');
        Route::post('/confirm-password', 'store');
    });

    /**
     * Şifre Güncelleme
     */
    Route::controller(PasswordController::class)->group(function () {
        Route::put('/password', 'update')->name('password.update');
    });

});
