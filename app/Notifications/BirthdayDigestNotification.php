<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class BirthdayDigestNotification extends GenericNotificationMessage
{
    // some custom actions could be applied here

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->line('BirthdayDigestNotification!')
    }
}
