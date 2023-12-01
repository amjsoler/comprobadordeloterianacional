<?php

namespace App\Notifications;

use App\Notifications\Canales\FirebaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Kreait\Firebase\Messaging\CloudMessage;

class PruebaQueuedBorrar extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $responseArray = array();

        if($notifiable->alertasporcorreo){
            array_push($responseArray, "mail");
        }

        if($notifiable->alertaspornotificacion){
            array_push($responseArray, FirebaseChannel::class);
        }

        return $responseArray;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Prueba de correo encolado')
                    ->line('Si estás leyendo esto parece que funciona');
    }

    public function toFirebase(object $notifiable): CloudMessage
    {
        return CloudMessage::withTarget("token", $notifiable->firebasetoken)
        ->withNotification(
            ["title" => "titledata", "body" => "bodydata"]
            )
            ->withData(["title" => "titledata", "body" => "bodydata"]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
