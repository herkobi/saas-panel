<?php

namespace App\Notifications;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionChangePlannedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Subscription $subscription;
    protected Plan $newPlan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription, Plan $newPlan)
    {
        $this->subscription = $subscription;
        $this->newPlan = $newPlan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $currentPlanName = $this->subscription->plan->name;
        $newPlanName = $this->newPlan->name;
        $changeDate = $this->subscription->change_scheduled_at->format('d.m.Y');

        return (new MailMessage)
            ->subject('Plan değişikliği planlandı')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line('Abonelik planınızın değiştirilmesi planlandı.')
            ->line('Değişiklik detayları:')
            ->line('Mevcut Plan: ' . $currentPlanName)
            ->line('Yeni Plan: ' . $newPlanName)
            ->line('Değişiklik Tarihi: ' . $changeDate)
            ->line('Değişiklik belirtilen tarihte otomatik olarak gerçekleştirilecektir.')
            ->action('Abonelik Bilgilerini Görüntüle', route('app.subscription.plans'))
            ->line('Bu değişikliği iptal etmek isterseniz, lütfen bizimle iletişime geçin.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'current_plan_id' => $this->subscription->plan_id,
            'current_plan_name' => $this->subscription->plan->name,
            'new_plan_id' => $this->newPlan->id,
            'new_plan_name' => $this->newPlan->name,
            'change_date' => $this->subscription->change_scheduled_at->format('Y-m-d H:i:s'),
            'title' => 'Plan değişikliği planlandı',
            'message' => $this->subscription->plan->name . ' planından ' . $this->newPlan->name . ' planına geçiş planlandı.',
        ];
    }
}
