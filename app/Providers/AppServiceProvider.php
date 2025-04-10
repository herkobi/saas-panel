<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Observers\SubscriptionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('tenant', function ($app) {
            return null;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Subscription::observe(SubscriptionObserver::class); // Yeni eklenen satır
    }
}
