<?php

namespace App\Notifications;

use App\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevosResultadosDisponibles extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $resultados)
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
        $responseMail = (new MailMessage)
            ->subject("Nuevo resultado disponible");

        foreach($this->resultados as $resultado)
        {
            $responseMail->line($resultado->fecha);

            $aux = "";

            foreach($resultado->premios as $res)
            {
                $aux .= Helpers::convertirNombrePremioANombreDeSistema($res["nombre"]) . ";" . $res["numero"] . ";" . $res["premio"] . "\n";
            }

            $responseMail->line($aux);
        }

        return $responseMail;
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
