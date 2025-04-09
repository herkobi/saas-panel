<?php

namespace App\Notifications;

use App\Models\Feature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeatureLimitWarningNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Feature $feature;
    protected int $threshold;
    protected int $currentUsage;
    protected int $limit;
    protected float $percentage;

    /**
     * Create a new notification instance.
     */
    public function __construct(Feature $feature, int $threshold, int $currentUsage, int $limit)
    {
        $this->feature = $feature;
        $this->threshold = $threshold;
        $this->currentUsage = $currentUsage;
        $this->limit = $limit;
        $this->percentage = round(($currentUsage / $limit) * 100, 1);
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
        return (new MailMessage)
            ->subject('Özellik Kullanım Limiti Uyarısı')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line($this->feature->name . ' özelliği için limit uyarısı.')
            ->line('Kullanım limitinizin ' . $this->percentage . '% oranına ulaştınız.')
            ->line('Mevcut kullanım: ' . $this->currentUsage . '/' . $this->limit)
            ->action('Kullanım Detaylarını Görüntüle', route('app.subscription.usage'))
            ->line('Kullanım limitinize yaklaşıyorsunuz. Limitinizi aşmamak için önlem almanızı veya daha yüksek bir plana geçmenizi öneririz.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'feature_id' => $this->feature->id,
            'feature_name' => $this->feature->name,
            'feature_slug' => $this->feature->slug,
            'threshold' => $this->threshold,
            'current_usage' => $this->currentUsage,
            'limit' => $this->limit,
            'percentage' => $this->percentage,
        ];
    }
}
