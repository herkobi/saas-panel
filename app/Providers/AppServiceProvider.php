<?php

namespace App\Providers;

use App\Notifications\AdminVerifyEmail;
use App\Models\Admin\Setting;
use App\Notifications\UserVerifyEmail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        /**
         * E-mail Verification
         */
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            if ($notifiable->guard == 'web') { // Örnek olarak 'web' guard kontrolü
                return (new UserVerifyEmail)->toMail($notifiable, $url);
            } else {
                // Diğer guard için özel bildirim sınıfını belirle
                return (new AdminVerifyEmail)->toMail($notifiable, $url);
            }
        });

        /**
         * Settings tablosundaki değerlere direk erişmeyi sağlıyor.
         * Kullanımı config('panel.userrole')
         * Kaynak: https://darkghosthunter.medium.com/laravel-loading-the-settings-from-the-database-or-file-9b4a3df5db75
         */

        // if not cli
        if (php_sapi_name() !== 'cli') {
            $settings = Setting::where('key', 'settings')->first();
            if ($settings) {
                $decodedValue = json_decode($settings->value, true);
                config(['panel' => $decodedValue]);
            }
        }
    }
}
