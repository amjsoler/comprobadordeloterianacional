<?php

namespace App\Notifications;

use App\Models\Decimo;
use App\Models\Sorteo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class ComprobacionDecimo extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $premioTotal, public $desglosePremios, public Decimo $decimo, public Sorteo $sorteo
    )
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
        $mail = (new MailMessage)
                ->subject("Resultados del sorteo del " . $this->sorteo->fecha)
                ->line('Ya están disponibles los resultados del sorteo ' . $this->sorteo->fecha);

        if($this->premioTotal > 0){
            $mail->line("Tu décimo " . $this->decimo->numero . " ha obtenido un premio total de " . $this->premioTotal . "¡Felicidades!");
        }else{
            $mail->line("Tu décimo " . $this->decimo->numero . " no ha obtenido premio" . "Una lástima. ¡Seguro que la próxima vez tienes más suerte!");
        }

        $mail->line("-----");
        $mail->line('¡Atención! Estos resultados no son oficiales y pueden contener algún error. Siempre es recomendable comprobar tus boletos en un punto oficial de venta');

        return $mail;
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
