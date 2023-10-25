<?php

namespace App\Listeners;

use App\Events\UsuarioRegistrado;
use App\Mail\VerificacionNuevoUsuario;
use App\Notifications\VerificarNuevaCuentaUsuario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoVerificacionCuenta
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UsuarioRegistrado $event): void
    {
        $event->usuario->notify(new VerificarNuevaCuentaUsuario($event->usuario));
    }
}
