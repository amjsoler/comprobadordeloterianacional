<?php

namespace App\Notifications\Canales;

use Illuminate\Notifications\Notification;

class FirebaseChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toFirebase($notifiable);

        //Aquí el código para mandar la notificación al token
        $messaging = app('firebase.messaging');
        $messaging->send($message);
    }
}
