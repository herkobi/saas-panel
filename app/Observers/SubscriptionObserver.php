<?php

namespace App\Observers;

use App\Enums\SubscriptionStatus;
use App\Models\Activity;
use App\Models\Subscription;
use App\Services\Tenant\NotificationService;
use Illuminate\Support\Facades\Auth;

class SubscriptionObserver
{
    protected $user;
    protected $notificationService;

    /**
     * Constructor
     */
    public function __construct(NotificationService $notificationService)
    {
        // Constructor içinde kullanıcıyı al
        $this->user = Auth::user();
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Subscription "created" event.
     */
    public function created(Subscription $subscription): void
    {
        // Yeni abonelik oluşturulduğunda
        $this->logActivity($subscription, 'subscription.created', [
            'action' => 'subscription_created',
            'plan_name' => $subscription->plan->name,
            'plan_id' => $subscription->plan_id,
            'billing_period' => $subscription->billing_period,
            'ends_at' => $subscription->ends_at?->format('Y-m-d H:i:s'),
            'is_recurring' => $subscription->is_recurring,
        ]);

        // Yeni abonelik oluşturulduğunda bildirim gönder
        $this->notifyTenantOwner($subscription, 'created');
    }

    /**
     * Handle the Subscription "updated" event.
     */
    public function updated(Subscription $subscription): void
    {
        // Plan değişikliği planlandıysa
        if ($subscription->wasChanged('scheduled_plan_id') && $subscription->scheduled_plan_id) {
            $this->logActivity($subscription, 'subscription.plan_change_scheduled', [
                'action' => 'plan_change_scheduled',
                'current_plan_id' => $subscription->plan_id,
                'current_plan_name' => $subscription->plan->name,
                'new_plan_id' => $subscription->scheduled_plan_id,
                'new_plan_name' => $subscription->scheduledPlan->name,
                'change_date' => $subscription->change_scheduled_at?->format('Y-m-d H:i:s'),
            ]);

            $this->notifyTenantOwner($subscription, 'plan_change_scheduled');
        }

        // İptal planlandıysa
        if ($subscription->wasChanged('cancellation_scheduled_at') && $subscription->cancellation_scheduled_at) {
            $this->logActivity($subscription, 'subscription.cancellation_scheduled', [
                'action' => 'cancellation_scheduled',
                'cancellation_date' => $subscription->cancellation_scheduled_at->format('Y-m-d H:i:s'),
                'reason' => $subscription->cancellation_reason
            ]);

            $this->notifyTenantOwner($subscription, 'cancellation_scheduled');
        }

        // Abonelik iptal edildiyse
        if ($subscription->wasChanged('cancelled_at') && $subscription->cancelled_at) {
            $this->logActivity($subscription, 'subscription.cancelled', [
                'action' => 'subscription_cancelled',
                'cancellation_date' => $subscription->cancelled_at->format('Y-m-d H:i:s'),
                'status' => $subscription->status->value,
                'status_label' => $subscription->status->getLabel(),
            ]);

            $this->notifyTenantOwner($subscription, 'cancelled');
        }

        // Abonelik durumu değişti mi?
        if ($subscription->wasChanged('status')) {
            $this->logActivity($subscription, 'subscription.status_changed', [
                'action' => 'subscription_status_changed',
                'old_status' => $subscription->getOriginal('status')?->getLabel(),
                'new_status' => $subscription->status->getLabel(),
            ]);

            // Farklı durumlara göre bildirim gönder
            if ($subscription->status === SubscriptionStatus::EXPIRED) {
                $this->notifyTenantOwner($subscription, 'expired');
            } elseif ($subscription->status === SubscriptionStatus::ACTIVE &&
                      $subscription->getOriginal('status') !== SubscriptionStatus::ACTIVE->value) {
                $this->notifyTenantOwner($subscription, 'activated');
            }
        }
    }

    /**
     * Tenant sahibine bildirim gönder
     */
    protected function notifyTenantOwner(Subscription $subscription, string $eventType): void
    {
        $tenant = $subscription->tenant;

        if (!$tenant) {
            return;
        }

        $owner = $tenant->users()->whereType('tenant_owner')->first();

        if (!$owner) {
            return;
        }

        switch ($eventType) {
            case 'created':
                $this->notificationService->notifySubscriptionCreated($subscription, $owner);
                break;
            case 'plan_change_scheduled':
                $this->notificationService->notifyPlanChangeScheduled($subscription, $owner);
                break;
            case 'cancellation_scheduled':
                // Eğer bu bildirim oluşturulursa notificationService'e eklenecek
                break;
            case 'cancelled':
                $this->notificationService->notifySubscriptionCancelled($subscription, $owner);
                break;
            case 'renewed':
                $this->notificationService->notifySubscriptionRenewed($subscription, $owner);
                break;
            case 'expired':
                $this->notificationService->notifySubscriptionExpired($subscription, $owner);
                break;

            case 'activated':
                $this->notificationService->notifySubscriptionActivated($subscription, $owner);
                break;
        }
    }

    /**
     * Activity log kaydı oluştur
     */
    protected function logActivity(Subscription $subscription, string $message, array $logData): void
    {
        Activity::create([
            'tenant_id' => $subscription->tenant_id,
            'user_id' => $this->user ? $this->user->id : null,
            'message' => $message,
            'log' => array_merge($logData, [
                'subscription_id' => $subscription->id,
                'model' => 'Subscription',
                'user' => $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ] : null,
            ]),
        ]);
    }
}
