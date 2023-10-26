<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;

class Sorteo extends Model
{
    use HasFactory;

    protected $table = "sorteos";

    //////////////////////
    ///// RELACIONES /////
    //////////////////////


    public function resultado() : HasOne
    {
        return $this->hasOne(Resultado::class, "sorteo", "id");
    }

    /////////////////////////////
    ///// MÉTODOS ESTÁTICOS /////
    /////////////////////////////

    /**
     * Método que devuelve el JSON de resultados de un sorteo pasado como param
     *
     * @param int $sorteoID El sorteo del cual extraer el resultado
     *
     * @return [Obj{tipo, numero, premio}]
     *  0: OK
     * -1: excepción
     * -2: No hay resultados
     */
    public static function dameResultadosDadoElSorteo(int $sorteoID)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al dameResultadosDadoElSorteo de Sorteo",
                array(
                    "request: " => compact("sorteoID")
                )
            );

            $resultados = Sorteo::find($sorteoID)->resultados;

            if($resultados){
                $response["code"] = 0;
                $response["data"] = $resultados;
            }else{
                $response["code"] = -2;
            }

            //Log de salida
            Log::debug("Saliendo del dameResultadosDadoElSorteo de Sorteo",
                array(
                    "request: " => compact("sorteoID"),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("sorteoID"),
                    "response: " => $response
                )
            );
        }

        return $response;
    }
}
