<?php

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Tenant $tenant;
    protected string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(Tenant $tenant, string $password)
    {
        $this->tenant = $tenant;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('login');

        return (new MailMessage)
            ->subject($this->tenant->name . ' - Ekibe Davet')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line($this->tenant->name . ' ekibine davet edildiniz.')
            ->line('Aşağıdaki bilgileri kullanarak sisteme giriş yapabilirsiniz:')
            ->line('E-posta: ' . $notifiable->email)
            ->line('Şifre: ' . $this->password)
            ->line('İlk girişinizden sonra şifrenizi değiştirmenizi öneririz.')
            ->action('Sisteme Giriş Yap', $url)
            ->line('Bu davet size ' . $this->tenant->owner()->name . ' tarafından gönderilmiştir.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tenant_id' => $this->tenant->id,
            'tenant_name' => $this->tenant->name,
            'message' => 'Ekibe davet edildiniz',
        ];
    }
}
