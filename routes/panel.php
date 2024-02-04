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
use App\Http\Controllers\Admin\Orders\{
    OrderController,
};
use App\Http\Controllers\Admin\Gateways\{
    GatewayController,
    BacController,
    PaytrController,
};
use App\Http\Controllers\Admin\Plans\{
    PlanController,
    PlanFeatureController
};
use App\Http\Controllers\Admin\Features\FeatureController;
use App\Http\Controllers\Admin\Users\{
    UserController,
};
use App\Http\Controllers\Admin\Settings\{
    CountryController,
    CurrencyController,
    PageController,
    PaymentController,
    SettingController,
    StateController,
    TaxController,
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
        Route::get('/accounts', 'index')->name('accounts');
        Route::get('/account/create', 'create')->name('account.create');
        Route::post('/account/create/store', 'store')->name('account.create.store');
        Route::get('/account/edit/{user}', 'edit')->name('account.edit');
        Route::post('/account/edit/update/{user}', 'update')->name('account.edit.update');
        Route::get('/account/detail/plan/{user}', 'plan')->name('account.plan.detail');
        Route::get('/account/detail/order/{user}', 'order')->name('account.order.detail');
        Route::get('/account/detail/activity/{user}', 'activity')->name('account.activity.detail');
    });

    /**
     * Sistem Kullanıcıları
     */
    Route::controller(UserController::class)->group(function(){
        Route::get('/users', 'index')->name('users');
        Route::get('/user/create', 'create')->name('user.create');
        Route::post('/user/create/store', 'store')->name('user.create.store');
        Route::get('/user/edit/{admin}', 'edit')->name('user.edit');
        Route::post('/user/edit/update/{admin}', 'update')->name('user.edit.update');
        Route::get('/user/detail/{admin}', 'detail')->name('user.detail');
        Route::get('/user/detail/auth/{admin}', 'auth')->name('user.auth.detail');
    });

    /**
     * Plan Yönetimi
     */
    Route::prefix('plans')->name('plans.')->group(function () {

        /**
         * Planlar
         */
        Route::controller(PlanController::class)->group(function(){
            Route::get('/plans', 'index')->name('plans');
            Route::get('/plan/edit/{plan}', 'edit')->name('plan.edit');
            Route::post('/plan/update/{plan}', 'update')->name('plan.update');
            Route::get('/plan/create', 'create')->name('plan.create');
            Route::post('/plan/create/store', 'store')->name('plan.create.store');
            Route::post('/plan/delete/{plan}', 'destroy')->name('plan.edit.delete');
            Route::get('/plan/deleted', 'deleted')->name('plan.deleted');
            Route::post('/plan/restore/{plan}', 'restore')->name('plan.restore')->withTrashed();
            Route::post('/plan/forcedelete', 'forcedelete')->name('plan.forcedelete');
        });

        /**
         * Plan Özellik İlişkisi
         */
        Route::controller(PlanFeatureController::class)->group(function(){
            Route::get('/add-feature/{plan}', 'index')->name('add.plan.to.feature');
            Route::post('/attach-feature/{plan}', 'update')->name('attach.feature.to plan');
            Route::get('/history/{plan}', 'history')->name('plan.history');

        });

        /**
         * Plan Özellikleri
         */
        Route::controller(FeatureController::class)->group(function(){
            Route::get('/features/all', 'index')->name('features');
            Route::get('/feature/create', 'create')->name('feature.create');
            Route::post('/feature/create/store', 'store')->name('feature.create.store');
            Route::get('/feature/edit/{feature}', 'edit')->name('feature.edit');
            Route::post('/feature/update/{feature}', 'update')->name('feature.edit.update');
            Route::post('/feature/delete/{feature}', 'destroy')->name('feature.edit.delete');
            Route::get('/features/deleted', 'deleted')->name('features.deleted');
            Route::post('features/restore/{feature}', 'restore')->name('feature.restore')->withTrashed();
            Route::post('features/forcedelete', 'forcedelete')->name('feature.forcedelete');
        });
    });

    /**
     * Ödemeler
     */
    Route::controller(OrderController::class)->group(function(){
        Route::get('/orders', 'index')->name('orders');
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
        Route::prefix('locations')->name('locations.')->group(function () {

            Route::controller(CountryController::class)->group(function(){
                Route::get('/countries', 'index')->name('countries');
                Route::get('/country/create', 'create')->name('country.create');
                Route::post('/country/create/store', 'store')->name('country.create.store');
                Route::get('/country/edit/{country}', 'edit')->name('country.edit');
                Route::post('/country/update/{country}', 'update')->name('country.update');
                Route::post('/country/destroy/{country}', 'destroy')->name('country.destroy');
            });

            Route::controller(StateController::class)->group(function(){
                Route::get('/states', 'index')->name('states');
                Route::get('/state/create', 'create')->name('state.create');
                Route::post('/state/create/store', 'store')->name('state.create.store');
                Route::get('/state/edit/{state}', 'edit')->name('state.edit');
                Route::post('/state/update/{state}', 'update')->name('state.update');
                Route::post('/state/destroy/{state}', 'destroy')->name('state.destroy');
            });
        });

        /**
         * Vergi Oranları
         */
        Route::controller(TaxController::class)->group(function(){
            Route::get('/taxes', 'index')->name('taxes');
            Route::get('/tax/create', 'create')->name('tax.create');
            Route::post('/tax/create/store', 'store')->name('tax.create.store');
            Route::get('/tax/edit/{tax}', 'edit')->name('tax.edit');
            Route::post('/tax/update/{tax}', 'update')->name('tax.update');
            Route::post('/tax/destroy/{tax}', 'destroy')->name('tax.destroy');
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
