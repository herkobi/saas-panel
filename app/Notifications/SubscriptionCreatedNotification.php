<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCreatedNotification extends Notification implements ShouldQueue
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
        $billingPeriod = $this->subscription->billing_period === 'monthly' ? 'Aylık' : 'Yıllık';
        $endDate = $this->subscription->ends_at ? $this->subscription->ends_at->format('d.m.Y') : 'Süresiz';
        
        return (new MailMessage)
            ->subject('Aboneliğiniz başarıyla oluşturuldu')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line($planName . ' planı aboneliğiniz başarıyla oluşturuldu.')
            ->line('Abonelik detayları:')
            ->line('Plan: ' . $planName)
            ->line('Dönem: ' . $billingPeriod)
            ->line('Bitiş Tarihi: ' . $endDate)
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
            'billing_period' => $this->subscription->billing_period,
            'ends_at' => $this->subscription->ends_at?->format('Y-m-d H:i:s'),
            'title' => 'Aboneliğiniz başarıyla oluşturuldu',
            'message' => $this->subscription->plan->name . ' planı aboneliğiniz başarıyla oluşturuldu.',
        ];
    }
}
