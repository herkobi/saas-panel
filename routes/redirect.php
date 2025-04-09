<?php

use App\Http\Controllers\Front\RedirectController;
use App\Http\Middleware\RedirectRateLimit;
use App\Http\Middleware\ValidateTenant;
use Illuminate\Support\Facades\Route;

// Her isteğe rate limiting uygula
Route::middleware(RedirectRateLimit::class)->group(function () {
    // Genel yönlendirme route'ları
    Route::get('/{id}', [RedirectController::class, 'index'])->name('redirect');
    Route::post('/{id}/password', [RedirectController::class, 'validatePassword'])->name('redirect.validate_password');
    Route::post('/{id}/consent', [RedirectController::class, 'validateConsent'])->name('redirect.validate_consent');

    // Yönetimle ilgili eylemler için tenant kontrolü de yap
    Route::middleware(ValidateTenant::class)->group(function () {
        Route::get('/{id}/preview', [RedirectController::class, 'index'])->name('redirect.preview');
    });
});

Route::fallback(function () {
    return abort(404);
});
