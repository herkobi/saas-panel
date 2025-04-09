<?php

use App\Traits\AuthUser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // AuthUser trait'ini kullanarak kullanıcı bilgilerini al
    $controller = new class {
        use AuthUser;

        public function __invoke()
        {
            $this->initializeAuthUser();

            // Kullanıcı giriş yapmışsa, tipine göre yönlendir
            if ($this->user) {
                if ($this->user->isAdmin()) {
                    return redirect()->route('panel.dashboard');
                } elseif ($this->user->isTenantUser()) {
                    return redirect()->route('app.dashboard');
                }
            }

            // Giriş yapmamış kullanıcılar için welcome sayfası
            return Inertia::render('Welcome');
        }
    };

    return $controller();
})->name('home');

// Auth routes
require __DIR__.'/auth.php';
