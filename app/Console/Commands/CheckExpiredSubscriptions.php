<?php

namespace App\Console\Commands;

use App\Models\Activity;
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
        $this->checkGraceSubscriptions();
        $this->checkNoGraceSubscriptions();
    }

    private function checkGraceSubscriptions()
    {
        $expiredGraceSubscriptions = Subscription::query()
            ->whereNotNull('grace_days_ended_at')
            ->where('grace_days_ended_at', '<=', now())
            ->whereNull('suppressed_at')
            ->get();

        foreach($expiredGraceSubscriptions as $subscription) {
            $subscription->suppress();

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
            $subscription->suppress();

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
}
