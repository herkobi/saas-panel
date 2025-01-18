<?php

namespace App\Notifications\User\Subscription;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LucasDotVin\Soulbscription\Models\Subscription;

class GraceStarted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Subscription $subscription,
        protected Tenant $tenant
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Abonelik Süreniz Dolmak Üzere')
            ->markdown('mail.subscription.grace-started', [
                'user' => $notifiable,
                'subscription' => $this->subscription,
                'tenant' => $this->tenant,
                'plan' => $this->subscription->plan,
                'grace_days_ended_at' => $this->subscription->grace_days_ended_at
            ]);
    }
}
