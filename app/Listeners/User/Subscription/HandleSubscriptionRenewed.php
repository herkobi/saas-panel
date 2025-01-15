<?php

namespace App\Listeners;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\AuthUser;
use App\Traits\LogActivity;
use LucasDotVin\Soulbscription\Events\SubscriptionRenewed;

class HandleSubscriptionRenewed
{
    use AuthUser, LogActivity;

    protected $loggingService;
    protected $activity;

    public function __construct(LoggingService $loggingService, Activity $activity)
    {
        $this->loggingService = $loggingService;
        $this->activity = $activity;
        $this->initializeAuthUser();
    }

    public function handle(SubscriptionRenewed $event): void
    {
        $subscription = $event->subscription;

        $this->loggingService->logUserAction(
            'subscription.renewed',
            $subscription->subscriber->tenant->code,
            $subscription,
            [
                'plan_id' => $subscription->plan_id,
                'expired_at' => $subscription->expired_at,
                'grace_days_ended_at' => $subscription->grace_days_ended_at,
                'amount' => $subscription->plan->price
            ]
        );

        Activity::create([
            'message' => 'subscription.renewed',
            'log' => $this->logActivity(
                'subscription renewed',
                $subscription->subscriber->tenant->code,
                $subscription->plan_id,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at,
                    'grace_days_ended_at' => $subscription->grace_days_ended_at,
                    'amount' => $subscription->plan->price
                ]
            ),
        ]);
    }
}
