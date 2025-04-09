<?php

namespace App\Services\Tenant;

use App\Models\Feature;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\FeatureLimitWarningNotification;
use App\Notifications\SubscriptionCancelledNotification;
use App\Notifications\SubscriptionChangePlannedNotification;
use App\Notifications\SubscriptionCreatedNotification;
use App\Notifications\SubscriptionExpiringNotification;
use App\Notifications\SubscriptionRenewedNotification;

class NotificationService
{
    /**
     * Abonelik Bildirimleri
     */

    /**
     * Abonelik oluşturulduğunda bildirim gönderir
     */
    public function notifySubscriptionCreated(Subscription $subscription, User $user)
    {
        $user->notify(new SubscriptionCreatedNotification($subscription));
    }

    /**
     * Abonelik sona ererken bildirim gönderir
     */
    public function notifySubscriptionExpiring(Subscription $subscription, User $user, int $daysLeft)
    {
        $user->notify(new SubscriptionExpiringNotification($subscription, $daysLeft));
    }

    /**
     * Abonelik iptal edildiğinde bildirim gönderir
     */
    public function notifySubscriptionCancelled(Subscription $subscription, User $user)
    {
        $user->notify(new SubscriptionCancelledNotification($subscription));
    }

    /**
     * Plan değişikliği planlandığında bildirim gönderir
     */
    public function notifyPlanChangeScheduled(Subscription $subscription, User $user)
    {
        $user->notify(new SubscriptionChangePlannedNotification($subscription, $subscription->scheduledPlan));
    }

    /**
     * Abonelik yenilendiğinde bildirim gönderir
     */
    public function notifySubscriptionRenewed(Subscription $subscription, User $user)
    {
        $user->notify(new SubscriptionRenewedNotification($subscription));
    }

    /**
     * Abonelik süresi dolduğunda bildirim gönderir
     */
    public function notifySubscriptionExpired(Subscription $subscription, User $user)
    {
        // Mevcut yapıda bu durum için SubscriptionCancelledNotification kullanılabilir
        $user->notify(new SubscriptionCancelledNotification($subscription));
    }

    /**
     * Abonelik aktifleştirildiğinde bildirim gönderir
     */
    public function notifySubscriptionActivated(Subscription $subscription, User $user)
    {
        // Mevcut yapıda bu durum için SubscriptionCreatedNotification kullanılabilir
        $user->notify(new SubscriptionCreatedNotification($subscription));
    }

    /**
     * Özellik & Limit Bildirimleri
     */

    /**
     * Özellik limiti uyarısı bildirimini gönderir
     */
    public function notifyFeatureLimitWarning(Feature $feature, User $user, int $threshold, int $currentUsage, int $limit)
    {
        $user->notify(new FeatureLimitWarningNotification($feature, $threshold, $currentUsage, $limit));
    }

    /**
     * Genel Bildirimler
     */

    /**
     * Genel bildirim gönderir
     */
    public function notify(User $user, $notification)
    {
        $user->notify($notification);
    }
}
