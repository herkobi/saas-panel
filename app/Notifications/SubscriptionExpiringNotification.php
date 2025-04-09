<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Subscription $subscription;
    protected int $daysLeft;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription, int $daysLeft)
    {
        $this->subscription = $subscription;
        $this->daysLeft = $daysLeft;
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
        $planName = $this->subscription->plan->name;
        
        return (new MailMessage)
            ->subject('Aboneliğiniz Yakında Sona Eriyor')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line($planName . ' planı için aboneliğiniz ' . $this->daysLeft . ' gün sonra sona erecek.')
            ->line('Aboneliğiniz otomatik olarak yenilenecektir. Aboneliğinizde değişiklik yapmak isterseniz, lütfen hesap ayarlarınızı kontrol edin.')
            ->action('Abonelik Bilgilerini Görüntüle', route('app.subscription.plans'))
            ->line('Hizmetimizi tercih ettiğiniz için teşekkür ederiz!');
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
            'plan_id' => $this->subscription->plan_id,
            'plan_name' => $this->subscription->plan->name,
            'days_left' => $this->daysLeft,
            'expires_at' => $this->subscription->ends_at,
        ];
    }
}