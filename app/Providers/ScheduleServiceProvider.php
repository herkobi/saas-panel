<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Schedule::class, function ($app): Schedule
        {
            return new Schedule;
        });
    }

    public function boot(): void
    {
        Schedule::command('app:check-expired-subscriptions')->daily();
    }
}
