<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class InvitationNotification extends GenericNotificationMessage
{
    // some custom actions could be applied here

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->line('InvitationNotification!')
    }
}
