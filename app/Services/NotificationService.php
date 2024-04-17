<?php

namespace App\Services;

use Illuminate\Notifications\AnonymousNotifiable;

class NotificationService
{
    public function sendAnonymously($notification)
    {
        $anonymousNotification = new AnonymousNotifiable();
        $anonymousNotification->notify($notification);
    }
}
