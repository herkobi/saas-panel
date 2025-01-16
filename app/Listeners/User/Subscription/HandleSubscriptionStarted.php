<?php

namespace App\Listeners\User\Subscription;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\AuthUser;
use App\Traits\LogActivity;
use LucasDotVin\Soulbscription\Events\SubscriptionStarted;

class HandleSubscriptionStarted
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
    public function handle(SubscriptionStarted $event): void
    {
        $subscription = $event->subscription;

        $this->loggingService->logUserAction(
            'new.subscription',
            $this->user->tenant->code,
            $subscription,
            [
                'plan_type' => $subscription->plan->price == 0 ? 'free' : 'paid',
                'started_at' => $subscription->started_at,
                'expired_at' => $subscription->expired_at
            ]
        );

        Activity::create([
            'message' => 'new.subscription',
            'log' => $this->logActivity(
                'user updated invoice detail of',
                $this->user->tenant->code,
                $subscription->plan_id,
                [
                    'plan_type' => $subscription->plan->price == 0 ? 'free' : 'paid',
                    'started_at' => $subscription->started_at,
                    'expired_at' => $subscription->expired_at
                ]
            ),
        ]);
    }
}
