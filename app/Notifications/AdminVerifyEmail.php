<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class AdminVerifyEmail extends VerifyEmailNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Özel E-posta Doğrulama Başlığı')
            ->line('Hesabınızı doğrulamak için aşağıdaki bağlantıya tıklayın.')
            ->action('Hesabınızı Doğrula', $this->verificationUrl($notifiable))
            ->line('Bu doğrulama bağlantısı :count dakika içinde geçerli olacaktır.', ['count' => config('auth.verification.expire')])
            ->line('Eğer bu işlemi siz yapmadıysanız, bu e-postayı görmezden gelin.');
    }

/**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        // Özel bir URL oluşturma mantığını burada uygulayabilirsiniz
        // Örneğin, URL'ye özel parametreler ekleyebilir veya farklı bir URL oluşturabilirsiniz

        // Eğer bir özel URL oluşturma callback'i tanımladıysanız, onu kullanın
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        // Özel URL mantığını buraya ekleyin
        return URL::temporarySignedRoute(
            'panel.verification.verify', // Özel bir rotayı kullanabilirsiniz
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
