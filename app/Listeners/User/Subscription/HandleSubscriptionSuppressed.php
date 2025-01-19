<?php

namespace App\Listeners\User\Subscription;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\AuthUser;
use App\Traits\LogActivity;
use LucasDotVin\Soulbscription\Events\SubscriptionSuppressed;

class HandleSubscriptionSuppressed
{
    use AuthUser, LogActivity;

    protected $loggingService;
    protected $activity;

    public function __construct(
        LoggingService $loggingService,
        Activity $activity,
    ) {
        $this->loggingService = $loggingService;
        $this->activity = $activity;
        $this->initializeAuthUser();
    }

    public function handle(SubscriptionSuppressed $event): void
    {
        $subscription = $event->subscription;
        $tenant = $subscription->subscriber;

        $tenantOwnerUser = $tenant->users()->where('is_tenant_owner', true)->first();

        // Grace period kontrolü
        if ($subscription->grace_days_ended_at) {

            $this->loggingService->logUserAction(
                'subscription.expired.suppressed.with.grace',
                $tenantOwnerUser,
                $subscription,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at,
                    'grace_days_ended_at' => $subscription->grace_days_ended_at
                ]
            );

            Activity::create([
                'user_id' => $tenantOwnerUser->id,
                'message' => 'subscription.expired.suppressed.with.grace',
                'log' => $this->logActivity(
                    'subscription expired and suppressed with grace period',
                    $tenantOwnerUser,
                    $subscription->plan_id,
                    [
                        'plan_id' => $subscription->plan_id,
                        'expired_at' => $subscription->expired_at,
                        'grace_days_ended_at' => $subscription->grace_days_ended_at
                    ]
                ),
            ]);
        } else {

            $this->loggingService->logUserAction(
                'subscription.expired.suppressed',
                $tenantOwnerUser,
                $subscription,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at
                ]
            );

            Activity::create([
                'user_id' => $tenantOwnerUser->id,
                'message' => 'subscription.expired.suppressed',
                'log' => $this->logActivity(
                    'subscription expired and suppressed',
                    $tenantOwnerUser,
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
