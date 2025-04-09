<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Subscription $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
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
            ->subject('Aboneliğiniz iptal edildi')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line($planName . ' planı aboneliğiniz iptal edildi.')
            ->line('Aboneliğinizin sağladığı tüm özellikler artık kullanılamaz durumda.')
            ->action('Yeni Abonelik Başlat', route('app.subscription.plans'))
            ->line('Umarız hizmetimizden memnun kaldınız. Yeniden görüşmek dileğiyle!');
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
            'cancelled_at' => $this->subscription->cancelled_at?->format('Y-m-d H:i:s'),
            'title' => 'Aboneliğiniz iptal edildi',
            'message' => $this->subscription->plan->name . ' planı aboneliğiniz iptal edildi.',
        ];
    }
}
