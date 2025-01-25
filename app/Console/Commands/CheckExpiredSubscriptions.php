<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Notifications\User\Subscription\Expired;
use App\Notifications\User\Subscription\GraceEnded;
use App\Notifications\User\Subscription\GraceStarted;
use App\Services\LoggingService;
use App\Traits\AuthUser;
use App\Traits\LogActivity;
use Illuminate\Console\Command;
use LucasDotVin\Soulbscription\Models\Subscription;

class CheckExpiredSubscriptions extends Command
{
    use AuthUser, LogActivity;

    protected $signature = 'app:check-expired-subscriptions';  // Bu satır önemli
    protected $description = 'Check expired subscriptions and handle them';

    protected $loggingService;
    protected $activity;

    public function __construct(LoggingService $loggingService, Activity $activity)
    {
        parent::__construct();
        $this->loggingService = $loggingService;
        $this->activity = $activity;
        $this->initializeAuthUser();
    }

    public function handle()
    {
        $this->checkGraceStarting(); // Önce grace başlangıçları
        $this->checkGraceSubscriptions(); // Sonra grace bitenler
        $this->checkNoGraceSubscriptions(); // En son normal süre bitenler
    }

    private function checkGraceSubscriptions()
    {
        $expiredGraceSubscriptions = Subscription::query()
            ->whereNotNull('grace_days_ended_at')
            ->where('grace_days_ended_at', '<=', now())
            ->whereNull('suppressed_at')
            ->get();

        foreach($expiredGraceSubscriptions as $subscription) {
            $tenant = $subscription->subscriber;

            // Grace period sonu bildirimi
            $tenant->owner->notify(new GraceEnded($subscription, $tenant));

            $this->loggingService->logUserAction(
                'subscription.expired.with.grace',
                $subscription->subscriber->tenant->code,
                $subscription,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at,
                    'grace_days_ended_at' => $subscription->grace_days_ended_at
                ]
            );

            Activity::create([
                'message' => 'subscription.expired.with.grace',
                'log' => $this->logActivity(
                    'subscription expired with grace',
                    $subscription->subscriber->tenant->code,
                    $subscription->plan_id,
                    [
                        'plan_id' => $subscription->plan_id,
                        'expired_at' => $subscription->expired_at,
                        'grace_days_ended_at' => $subscription->grace_days_ended_at
                    ]
                ),
            ]);
        }
    }

    private function checkNoGraceSubscriptions()
    {
        $expiredNoGraceSubscriptions = Subscription::query()
            ->whereNull('grace_days_ended_at')
            ->where('expired_at', '<=', now())
            ->whereNull('suppressed_at')
            ->get();

        foreach($expiredNoGraceSubscriptions as $subscription) {
            $tenant = $subscription->subscriber;

            // Normal süre bitiş bildirimi
            $tenant->owner->notify(new Expired($subscription, $tenant));

            $this->loggingService->logUserAction(
                'subscription.expired',
                $subscription->subscriber->tenant->code,
                $subscription,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at
                ]
            );

            Activity::create([
                'message' => 'subscription.expired',
                'log' => $this->logActivity(
                    'subscription expired',
                    $subscription->subscriber->tenant->code,
                    $subscription->plan_id,
                    [
                        'plan_id' => $subscription->plan_id,
                        'expired_at' => $subscription->expired_at
                    ]
                ),
            ]);
        }
    }

    private function checkGraceStarting()
    {
        $startingGraceSubscriptions = Subscription::query()
            ->whereNotNull('grace_days_ended_at')
            ->where('expired_at', '<=', now())
            ->where('grace_days_ended_at', '>', now())
            ->whereNull('suppressed_at')
            ->get();

        foreach($startingGraceSubscriptions as $subscription) {
            $tenant = $subscription->subscriber;

            // Grace period başlangıç bildirimi
            $tenant->owner->notify(new GraceStarted($subscription, $tenant));

            // Logging işlemleri
            $this->loggingService->logUserAction(
                'subscription.grace.started',
                $tenant->owner,
                $subscription,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at,
                    'grace_days_ended_at' => $subscription->grace_days_ended_at
                ]
            );

            Activity::create([
                'message' => 'subscription.grace.started',
                'log' => $this->logActivity(
                    'subscription grace period started',
                    $tenant->owner,
                    $subscription->plan_id,
                    [
                        'plan_id' => $subscription->plan_id,
                        'expired_at' => $subscription->expired_at,
                        'grace_days_ended_at' => $subscription->grace_days_ended_at
                    ]
                ),
            ]);
        }
    }
}
