<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Link;
use App\Models\Subscription;
use App\Observers\CampaignObserver;
use App\Observers\LinkObserver;
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
        Campaign::observe(CampaignObserver::class);
        Link::observe(LinkObserver::class);
        Subscription::observe(SubscriptionObserver::class); // Yeni eklenen satır
    }
}
