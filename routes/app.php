<?php

use App\Http\Controllers\User\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController,
};

use App\Http\Controllers\User\Profile\{
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

Route::redirect('/app', '/app/login');

Route::middleware(['guest:web'])->prefix('app')->name('app.')->group(function () {

    Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::get('/login', 'create')->name('login');
        Route::post('/login', 'store')->name('login.store');
    });

    Route::controller(RegisteredUserController::class)->group(function () {
        Route::get('/register', 'create')->name('register');
        Route::post('/register', 'store')->name('register.store');
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

Route::middleware(['auth:web'])->prefix('app')->name('app.')->group(function () {

    Route::get('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    Route::middleware(['signed', 'throttle:6,1'])->group(function () {
        Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->name('verification.verify');
    });

    Route::middleware('throttle:6,1')->controller(EmailVerificationNotificationController::class)->group(function () {
        Route::post('/email/verification-notification', 'store')->name('verification.send');
    });

    Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::post('/logout', 'destroy')->name('app.logout');
    });

});

Route::middleware(['auth:web', 'auth.session', 'verified'])->prefix('app')->name('app.')->group(function () {

    /**
     * Başlangıç
     */
    Route::get('dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

    /**
     * Hesap Yönetimi
     */
    Route::prefix('account')->name('account.')->group(function () {
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
