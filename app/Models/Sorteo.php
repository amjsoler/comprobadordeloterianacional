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

    /**
     * Método que devuelve un array de fechas que coincidan con las fechas de sorteos pasados por parametro
     *
     * @param array $fechas Las fechas a buscar
     *
     * @return [fecha]
     *  0: OK
     * -1: Excepción
     */
    public static function dameFechasSorteoInArrayDeFechas(array $fechas)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al dameFechasSorteoInArrayDeFechas de Sorteo",
                array(
                    "request: " => compact("fechas")
                )
            );

            $sorteosExistentes = Sorteo::whereIn("fecha", $fechas)->get("fecha");

            $response["code"] = 0;
            $response["data"] = $sorteosExistentes;

        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("fechas"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del dameFechasSorteoInArrayDeFechas de Sorteo",
            array(
                "request: " => compact("fechas"),
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método para crear un conjunto de sorteos dado un array de sorteos
     *
     * @param [{nombre, fecha, numero_sorteo}] $sorteosAInsertar Los sorteos a insertar
     *
     * @return void
     *  0: OK
     * -1: Excepción
     */
    public static function crearSorteosDadoUnArrayDeSorteos($sorteosAInsertar)
    {
        $response = [];

        Log::debug("Entrando al crearSorteosDadoUnArrayDeSorteos del modelo Sorteo",
            array(
                "request: " => compact("sorteosAInsertar")
            )
        );

        try{
            foreach($sorteosAInsertar as $insertar){
                $sort = new Sorteo();
                $sort->nombre = $insertar->nombre;
                $sort->fecha = $insertar->fecha;
                $sort->numero_sorteo = $insertar->numero_sorteo;
                $sort->save();
            }

            $response["code"] = 0;
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("sorteosAInsertar"),
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del crearSorteosDadoUnArrayDeSorteos del modelo Sorteo",
            array(
                "request: " => compact("sorteosAInsertar"),
                "response: " => $response
            )
        );

        return $response;
    }
}
