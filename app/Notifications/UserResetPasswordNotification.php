<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class UserResetPasswordNotification extends ResetPasswordNotification
{
    /**
     * Get the reset URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('app.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }

}
