<?php

namespace App\Listeners;

use App\Events\NuevosResultadosGuardados;
use App\Helpers\Helpers;
use App\Models\Decimo;
use App\Models\User;
use App\Notifications\ComprobacionDecimo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ComprobarPremiosDecimosDelResultadoGuardado implements ShouldQueue
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
    public function handle(NuevosResultadosGuardados $event): void
    {
        //Me traigo los décimos con el premio vacío y que referencien al sorteo del que hemos publicado sus resultados
        $decimos = Decimo::dameDecimosSinComprobarDadoElSorteoWithUser($event->sorteo);

        if($decimos["code"] == 0){
            $decimos = $decimos["data"];

            foreach($decimos as $decimo){
                $result = Helpers::comprobarDecimo($event->sorteo->resultados, $decimo->numero, $decimo->reintegro, $decimo->serie, $decimo->fraccion);

                if($result["code"] == 0){
                    $premios = $result["data"];

                    $decimo->usuarioPropietario->notify(new ComprobacionDecimo(
                        $premios["premioTotal"],
                        $premios["premiosObtenidos"],
                        $decimo,
                        $event->sorteo));

                    $result = Decimo::asignarPremio($decimo, $premios["premioTotal"]);

                    if($result["code"] == 0){
                        Log::debug("Décimo comprobado y notificado al usuario");
                    }else{
                        Log::error("Error al almacenar el premio en el décimo. Comprobar qué ha pasado");
                    }
                }else{
                    Log::error("Error al comprobar el décimo, no se ha podido mandar notificación al usuario");
                }
            }
        }
        else{
            Log::error("Error al leer los décimos dado el sorteo");
        }
    }
}
