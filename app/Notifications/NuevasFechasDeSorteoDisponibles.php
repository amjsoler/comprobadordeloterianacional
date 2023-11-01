<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevasFechasDeSorteoDisponibles extends Notification
{
    use Queueable;

    public function __construct(public $sorteosDisponibles)
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
        $msg = (new MailMessage)
            ->line('Nuevas fechas de sorteo encontradas');

        foreach ($this->sorteosDisponibles as $sorteo) {
            $msg->line($sorteo->fecha);
            $msg->line($sorteo->nombre);
            $msg->line($sorteo->numero_sorteo);
            $msg->line("-----");
        }

        return $msg;
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
