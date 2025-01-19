<?php

namespace App\Listeners\User\Subscription;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\LogActivity;
use LucasDotVin\Soulbscription\Events\SubscriptionStarted;

class HandleSubscriptionStarted
{
    use LogActivity;

    protected $loggingService;
    protected $activity;

    public function __construct(LoggingService $loggingService, Activity $activity)
    {
        $this->loggingService = $loggingService;
        $this->activity = $activity;
    }

    public function handle(SubscriptionStarted $event): void
    {
        $subscription = $event->subscription;
        $tenant = $subscription->subscriber;

        $tenantOwnerUser = $tenant->users()->where('is_tenant_owner', true)->first();

        $this->loggingService->logUserAction(
            'new.subscription',
            $tenantOwnerUser,
            $subscription,
            [
                'plan_type' => $subscription->plan->price == 0 ? 'free' : 'paid',
                'started_at' => $subscription->started_at,
                'expired_at' => $subscription->expired_at
            ]
        );

        Activity::create([
            'user_id' => $tenantOwnerUser->id,
            'message' => 'new.subscription',
            'log' => $this->logActivity(
                'subscription started',
                $tenantOwnerUser,
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
