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

    public function __construct(LoggingService $loggingService, Activity $activity)
    {
        $this->loggingService = $loggingService;
        $this->activity = $activity;
        $this->initializeAuthUser();
    }

    public function handle(SubscriptionSuppressed $event): void
    {
        $subscription = $event->subscription;

        // Eğer expired_at tarihinden sonra suppress edildiyse (süresi bittiği için)
        if($subscription->expired_at && $subscription->expired_at->isPast()) {
            $this->loggingService->logUserAction(
                'subscription.expired.suppressed',
                $subscription->subscriber->tenant->code,
                $subscription,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at,
                    'grace_days_ended_at' => $subscription->grace_days_ended_at
                ]
            );

            Activity::create([
                'message' => 'subscription.expired.suppressed',
                'log' => $this->logActivity(
                    'subscription expired and suppressed',
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
}
