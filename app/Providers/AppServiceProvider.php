<?php

namespace App\Providers;

use App\Models\Admin\Setting;
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
