<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class AdminVerifyEmail extends VerifyEmail
{
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
