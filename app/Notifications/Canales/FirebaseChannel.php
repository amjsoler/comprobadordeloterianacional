<?php

namespace App\Notifications\Canales;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Contract\Messaging;

class FirebaseChannel
{
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toFirebase($notifiable);

        //Aquí el código para mandar la notificación al token
        $this->messaging->send($message);
    }
}
