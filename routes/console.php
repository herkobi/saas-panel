<?php

use App\Services\Tenant\SubscriptionService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('inspire')->hourly();

// Abonelik Sistemi Zamanlanmış Görevleri
Schedule::command('subscription:check-expiring --days=3')->dailyAt('09:00');
Schedule::command('subscription:process-scheduled-changes')->hourly();
Schedule::command('subscription:renew')->dailyAt('01:00');

// Mevcut processScheduledChanges metodu için uyumluluk
// Geçiş sürecinde hem eski hem yeni metodu çalıştırabiliriz
Schedule::call(function () {
    app(SubscriptionService::class)->processScheduledChanges();
})->hourly();
