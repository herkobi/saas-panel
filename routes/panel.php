<?php

use App\Http\Controllers\Admin\Accounts\AccountGroupsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Accounts\AccountsController;
use App\Http\Controllers\Admin\Feature\FeatureController;
use App\Http\Controllers\Admin\Orders\OrdersController;
use App\Http\Controllers\Admin\Plan\PlanController;
use App\Http\Controllers\Admin\Profile\ProfileController;
use App\Http\Controllers\Admin\Settings\{
    AdminAgreementController,
    AgreementVersionController,
    BacsController,
    PagesController,
    PaytrController,
    SettingsController,
    UsersController,
    UserAgreementController,
};
use App\Http\Controllers\Admin\Tools\{
    ActivitiesController,
    AuthLogsController,
    CacheController,
    CountryController,
    CurrencyController,
    LanguageController,
    OrderstatusController,
    StateController,
    TaxController
};
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'auth.session', 'verified', 'panel:admin', 'system.settings'])->prefix('panel')->name('panel.')->group(function () {

    //Route::middleware(['aggrementcheck'])->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('home');
            Route::get('/passive', 'passive')->name('passive');
            Route::get('/sign', 'sign')->name('sign.agreement');
            Route::post('/accept-agreement', 'accept')->name('agreement.accept');
        });

        Route::controller(AccountsController::class)->group( function() {
            Route::get('/accounts', 'index')->name('accounts');
            Route::get('/account/detail/{user}', 'detail')->name('account.detail');
            Route::get('/account/authlogs/{user}', 'authlogs')->name('account.authlogs');
            Route::get('/account/create', 'create')->name('account.create');
            Route::post('/account/store', 'store')->name('account.store');
            Route::delete('/account/delete/{user}', 'destroy')->name('account.delete');

            Route::post('/account/status-update/{user}', 'statusUpdate')->name('account.status.update');
            Route::post('/account/change-email/{user}', 'changeEmail')->name('account.change.email');
            Route::post('/account/verify-email/{user}', 'verifyEmail')->name('account.verify.email');
            Route::post('/account/check-email/{user}', 'checkEmail')->name('account.check.email');
            Route::post('/account/change-password/{user}', 'changePassword')->name('account.change.password');
            Route::post('/account/reset-password/{user}', 'resetPassword')->name('account.reset.password');

            Route::get('/accounts/latest', 'latest')->name('accounts.latest');
            Route::get('/accounts/unverified', 'unverified')->name('accounts.unverified');
            Route::get('/accounts/inactive', 'inactive')->name('accounts.inactive');

            Route::get('/accounts/draft', 'draft')->name('accounts.draft');
            Route::get('/accounts/passive', 'passive')->name('accounts.passive');
            Route::get('/accounts/deleted', 'deleted')->name('accounts.deleted');
        });

        Route::controller(AccountGroupsController::class)->group( function() {
            Route::get('/account-groups', 'index')->name('accountgroups');
            Route::get('/account-group/create', 'create')->name('accountgroup.create');
            Route::post('/account-group/store', 'store')->name('accountgroup.store');
            Route::get('/account-group/edit/{accountgroup}', 'edit')->name('accountgroup.edit');
            Route::post('/account-group/update/{accountgroup}', 'update')->name('accountgroup.update');
            Route::get('/account-group/detail/{accountgroup}', 'detail')->name('accountgroup.detail');
            Route::delete('/account-group/delete/{accountgroup}', 'destroy')->name('accountgroup.delete');
        });

        Route::controller(OrdersController::class)->group(function () {
            Route::get('/orders', 'index')->name('orders');
            Route::get('/order/invoiced', 'invoiced')->name('order.invoiced');
            Route::get('/order/pending', 'pending')->name('order.pending');
            Route::get('/order/rejected', 'rejected')->name('order.rejected');
            Route::get('/order/{order:code}', 'show')->name('order.show');
            Route::post('/order/{order:code}/approve', 'approve')->name('order.approve');
            Route::post('/order/{order:code}/reject', 'reject')->name('order.reject');  // Yeni eklenen
        });

        Route::controller(PlanController::class)->group( function () {
            Route::get('/plans', 'index')->name('plans');
            Route::get('/plan/create', 'create')->name('plan.create');
            Route::post('/plan/store', 'store')->name('plan.store');
            Route::get('/plan/edit/{plan}', 'edit')->name('plan.edit');
            Route::post('/plan/update/{plan}', 'update')->name('plan.update');
            Route::delete('/plan/delete/{plan}', 'destroy')->name('plan.delete');
            Route::post('/plan/restore/{plan}', 'restore')->name('plan.restore');
            Route::delete('/plan/force-delete/{plan}', 'force')->name('plan.force-delete');
        });

        Route::controller(FeatureController::class)->group( function() {
            Route::get('/features', 'index')->name('features');
            Route::get('/feature/create', 'create')->name('feature.create');
            Route::post('/feature/store', 'store')->name('feature.store');
            Route::get('/feature/edit/{feature}', 'edit')->name('feature.edit');
            Route::post('/feature/update/{feature}', 'update')->name('feature.update');
            Route::delete('/feature/delete/{feature}', 'destroy')->name('feature.delete');
            Route::post('/feature/restore/{feature}', 'restore')->name('feature.restore');
            Route::delete('/feature/force-delete/{feature}', 'force')->name('feature.force-delete');
        });

        Route::prefix('tools')->name('tools.')->group( function() {

            Route::prefix('config')->name('config.')->group(function() {
                Route::controller(CountryController::class)->group( function() {
                    Route::get('/countries', 'index')->name('countries');
                    Route::get('/country/create', 'create')->name('country.create');
                    Route::post('/country/store', 'store')->name('country.store');
                    Route::get('/country/edit/{country}', 'edit')->name('country.edit');
                    Route::post('/country/update/{country}', 'update')->name('country.update');
                    Route::delete('/country/delete/{country}', 'destroy')->name('country.delete');

                    Route::get('/countries/states', 'getStates')->name('countries.states');
                });

                Route::controller(StateController::class)->group( function() {
                    Route::get('/states/{country}', 'index')->name('states');
                    Route::get('/state/{country}/create', 'create')->name('state.create');
                    Route::post('/state/{country}/store', 'store')->name('state.store');
                    Route::get('/state/edit/{state}', 'edit')->name('state.edit');
                    Route::post('/state/update/{state}', 'update')->name('state.update');
                    Route::delete('/state/delete/{state}', 'destroy')->name('state.delete');
                });

                Route::controller(LanguageController::class)->group( function() {
                    Route::get('/languages', 'index')->name('languages');
                    Route::get('/language/create', 'create')->name('language.create');
                    Route::post('/language/store', 'store')->name('language.store');
                    Route::get('/language/edit/{language}', 'edit')->name('language.edit');
                    Route::post('/language/update/{language}', 'update')->name('language.update');
                    Route::delete('/language/delete/{language}', 'destroy')->name('language.delete');
                });

                Route::controller(CurrencyController::class)->group( function() {
                    Route::get('/currencies', 'index')->name('currencies');
                    Route::get('/currency/create', 'create')->name('currency.create');
                    Route::post('/currency/store', 'store')->name('currency.store');
                    Route::get('/currency/edit/{currency}', 'edit')->name('currency.edit');
                    Route::post('/currency/update/{currency}', 'update')->name('currency.update');
                    Route::delete('/currency/delete/{currency}', 'destroy')->name('currency.delete');
                });

                Route::controller(TaxController::class)->group( function() {
                    Route::get('/taxes', 'index')->name('taxes');
                    Route::get('/tax/create', 'create')->name('tax.create');
                    Route::post('/tax/store', 'store')->name('tax.store');
                    Route::get('/tax/edit/{tax}', 'edit')->name('tax.edit');
                    Route::post('/tax/update/{tax}', 'update')->name('tax.update');
                    Route::delete('/tax/delete/{tax}', 'destroy')->name('tax.delete');
                });

                Route::controller(OrderstatusController::class)->group( function() {
                    Route::get('/orderstatuses', 'index')->name('orderstatuses');
                    Route::get('/orderstatus/create', 'create')->name('orderstatus.create');
                    Route::post('/orderstatus/store', 'store')->name('orderstatus.store');
                    Route::get('/orderstatus/edit/{orderstatus}', 'edit')->name('orderstatus.edit');
                    Route::post('/orderstatus/update/{orderstatus}', 'update')->name('orderstatus.update');
                    Route::delete('/orderstatus/delete/{orderstatus}', 'destroy')->name('orderstatus.delete');
                });
            });

            Route::controller(CacheController::class)->group( function() {
                Route::get('/cache', 'index')->name('cache');
                Route::post('/cache-clear', 'cache')->name('cache.clear');
                Route::post('/optimize-clear', 'optimize')->name('optimize.clear');
                Route::post('/view-clear', 'view')->name('view.clear');
                Route::post('/route-clear', 'route')->name('route.clear');
                Route::post('/config-clear', 'config')->name('config.clear');
                Route::post('/event-clear', 'event')->name('event.clear');
            });

            Route::controller(ActivitiesController::class)->group( function() {
                Route::get('/users-activities', 'users')->name('users.activities');
                Route::get('/admins-activities', 'admins')->name('admins.activities');
                Route::get('/passwords-activities', 'passwords')->name('passwords.activities');
            });

            Route::controller(AuthLogsController::class)->group( function() {
                Route::get('/users-auth-logs', 'users')->name('users.authLogs');
                Route::get('/admin-auth-logs', 'admins')->name('admins.authLogs');
            });

            Route::controller(LogViewerController::class)->group( function() {
                Route::get('/logs', 'index')->name('logs');
            });
        });

        Route::prefix('settings')->name('settings.')->group( function() {
            Route::controller(SettingsController::class)->group( function() {
                Route::get('/general', 'index')->name('general');
                Route::post('/general/update', 'updateGeneral')->name('general.update');
                Route::get('/system', 'system')->name('system');
                Route::post('/system/update', 'updateSystem')->name('system.update');
            });

            Route::prefix('payments')->name('payments.')->group( function() {
                Route::controller(BacsController::class)->group( function() {
                    Route::get('/bacs', 'index')->name('bacs');
                    Route::get('/bac/create', 'create')->name('bac.create');
                    Route::post('/bac/store', 'store')->name('bac.store');
                    Route::get('/bac/edit/{bac}', 'edit')->name('bac.edit');
                    Route::post('/bac/update/{bac}', 'update')->name('bac.update');
                    Route::delete('/bac/delete/{bac}', 'destroy')->name('bac.delete');
                });

                Route::controller(PaytrController::class)->group( function() {
                    Route::get('/paytr', 'edit')->name('paytr');
                    Route::post('/paytr-update', 'update')->name('paytr.update');
                });
            });

            Route::controller(UserAgreementController::class)->group( function() {
                Route::get('/agreements/user', 'index')->name('agreements.user');
                Route::get('/agreement/user/create', 'create')->name('agreement.user.create');
                Route::post('/agreement/user/store', 'store')->name('agreement.user.store');
                Route::get('/agreement/user/agreement/{agreement}', 'edit')->name('agreement.user.edit');
                Route::post('/agreement/user/update/{agreement}', 'update')->name('agreement.user.update');
                Route::delete('/agreement/user/agreement/{agreement}', 'destroy')->name('agreement.user.delete');
            });

            Route::controller(AdminAgreementController::class)->group( function() {
                Route::get('/agreements/admin', 'index')->name('agreements.admin');
                Route::get('/agreement/admin/create', 'create')->name('agreement.admin.create');
                Route::post('/agreement/admin/store', 'store')->name('agreement.admin.store');
                Route::get('/agreement/admin/agreement/{agreement}', 'edit')->name('agreement.admin.edit');
                Route::post('/agreement/admin/update/{agreement}', 'update')->name('agreement.admin.update');
                Route::delete('/agreement/admin/agreement/{agreement}', 'destroy')->name('agreement.admin.delete');
                Route::get('/agreements/signatures', 'signatures')->name('agreement.signatures');
                Route::get('/agreements/{agreement}/signatures', 'agreementSignatures')->name('agreement.agreementSignatures');
            });

            Route::controller(AgreementVersionController::class)->group( function() {
                Route::get('/agreement/{agreement}/versions', 'index')->name('agreement.version.detail');
                Route::get('/agreement/{agreement}/version/create', 'create')->name('agreement.version.create');
                Route::post('/agreement/{agreement}/version/store', 'store')->name('agreement.version.store');
                Route::get('/agreement/{agreement}/version/edit/{version}', 'edit')->name('agreement.version.edit');
                Route::post('/agreement/{agreement}/version/{version}/update', 'update')->name('agreement.version.update');
                Route::delete('/agreement/{agreement}/version/{version}', 'destroy')->name('agreement.version.destroy');
                Route::post('/agreement/{agreement}/version/{version}/publish', 'publish')->name('agreement.version.publish');
                Route::get('/agreement/{agreement}/version/detail/{version}', 'show')->name('agreement.version.show');
            });

            Route::controller(PagesController::class)->group( function() {
                Route::get('/pages', 'index')->name('pages');
                Route::get('/page/create', 'create')->name('page.create');
                Route::post('/page/store', 'store')->name('page.store');
                Route::get('/page/edit/{page}', 'edit')->name('page.edit');
                Route::post('/page/update/{page}', 'update')->name('page.update');
                Route::delete('/page/delete/{page}', 'destroy')->name('page.delete');
            });

            Route::controller(UsersController::class)->group( function() {
                Route::get('/users', 'index')->name('users');
                Route::get('/user/detail/{user}', 'detail')->name('user.detail');
                Route::get('/user/authlogs/{user}', 'authlogs')->name('user.authlogs');
                Route::get('/user/create', 'create')->name('user.create');
                Route::post('/user/store', 'store')->name('user.store');
                Route::delete('/user/delete/{user}', 'destroy')->name('user.delete');

                Route::post('/user/status-update/{user}', 'statusUpdate')->name('user.status.update');
                Route::post('/user/change-email/{user}', 'changeEmail')->name('user.change.email');
                Route::post('/user/verify-email/{user}', 'verifyEmail')->name('user.verify.email');
                Route::post('/user/check-email/{user}', 'checkEmail')->name('user.check.email');
                Route::post('/user/reset-password/{user}', 'resetPassword')->name('user.reset.password');
            });
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
    //});

});
