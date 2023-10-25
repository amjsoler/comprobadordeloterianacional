<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecuperarCuenta extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $token)
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route("recuperarcuentaget", $this->token);

        return (new MailMessage)
            ->subject("Recupera tu cuenta")
            ->line('Este mensaje se ha generado de forma automática para que restablezcas la contraseña de tu cuenta de usuario.')
            ->line('Para hacerlo, solo tienes que pulsar en el enlace que encontrarás a continuación:')
            ->action('Recuperar cuenta', $url)
            ->line('Un saludo desde ' . env("APP_NAME"));
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
