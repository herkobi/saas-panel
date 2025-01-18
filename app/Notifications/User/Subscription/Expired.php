<?php

namespace App\Notifications\User\Subscription;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LucasDotVin\Soulbscription\Models\Subscription;

class Expired extends Notification implements ShouldQueue
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
            ->subject('Aboneliğiniz Sona Erdi')
            ->markdown('mail.subscription.expired', [
                'user' => $notifiable,
                'subscription' => $this->subscription,
                'tenant' => $this->tenant,
                'plan' => $this->subscription->plan,
                'has_grace' => !is_null($this->subscription->grace_days_ended_at)
            ]);
    }
}
