<?php

namespace App\Listeners\User\Subscription;

use App\Actions\User\Tenant\ChangeStatus;
use App\Enums\AccountStatus;
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
    protected $changeStatus;

    public function __construct(
        LoggingService $loggingService,
        Activity $activity,
        ChangeStatus $changeStatus
    ) {
        $this->loggingService = $loggingService;
        $this->activity = $activity;
        $this->changeStatus = $changeStatus;
        $this->initializeAuthUser();
    }

    public function handle(SubscriptionSuppressed $event): void
    {
        $subscription = $event->subscription;
        $tenant = $subscription->subscriber;

        // Grace period kontrolü
        if ($subscription->grace_days_ended_at) {
            // Grace period varsa DRAFT'a çek
            $this->changeStatus->execute($tenant, AccountStatus::DRAFT);

            $this->loggingService->logUserAction(
                'subscription.expired.suppressed.with.grace',
                $this->user,
                $subscription,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at,
                    'grace_days_ended_at' => $subscription->grace_days_ended_at
                ]
            );

            Activity::create([
                'message' => 'subscription.expired.suppressed.with.grace',
                'log' => $this->logActivity(
                    'subscription expired and suppressed with grace period',
                    $this->user,
                    $subscription->plan_id,
                    [
                        'plan_id' => $subscription->plan_id,
                        'expired_at' => $subscription->expired_at,
                        'grace_days_ended_at' => $subscription->grace_days_ended_at
                    ]
                ),
            ]);
        } else {
            // Grace period yoksa direkt PASSIVE'e çek
            $this->changeStatus->execute($tenant, AccountStatus::PASSIVE);

            $this->loggingService->logUserAction(
                'subscription.expired.suppressed',
                $this->user,
                $subscription,
                [
                    'plan_id' => $subscription->plan_id,
                    'expired_at' => $subscription->expired_at
                ]
            );

            Activity::create([
                'message' => 'subscription.expired.suppressed',
                'log' => $this->logActivity(
                    'subscription expired and suppressed',
                    $this->user,
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
