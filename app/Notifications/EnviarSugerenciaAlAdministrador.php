<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnviarSugerenciaAlAdministrador extends Notification
{
    use Queueable;

    private string $texto;
    private string $sender;
    /**
     * Create a new notification instance.
     */
    public function __construct(string $texto, string $sender)
    {
        $this->texto = $texto;
        $this->sender = $sender;
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
        return (new MailMessage)
            ->subject("Nueva sugerencia realizada")
            ->line($this->sender . "ha realizado la siguiente sugerencia")
            ->line($this->texto);
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
