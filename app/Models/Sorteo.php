<?php

namespace App\Models;

use App\Helpers\Helpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Sorteo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "sorteos";

    //////////////////////
    ///// RELACIONES /////
    //////////////////////



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

            $resultados = Sorteo::find($sorteoID);

            if($resultados && $resultados->resultados){
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
     * @param array $fechas Las fechas a buscar en formato Y-m-d
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
     * Devuelve las fechas de sorteos existentes sin resutlados en BD y los cruzas con la lista dada por param
     *
     * @return [fechas]
     *  0: OK
     * -1: Excepción
     * -2: Error en la consulta
     */
    public static function cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas($fechas)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try {
            //Log de entrada
            Log::debug("Entrando al cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas de Resultado");

            $fechasSorteosExistentesSinResultados = Sorteo::whereNull("resultados")
                ->whereIn("fecha", $fechas)
                ->get("fecha")
                ->toArray();

            $response["code"] = 0;
            $response["data"] = $fechasSorteosExistentesSinResultados;

        } catch (Exception $e) {
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas de Resultado",
            array(
                "response: " => $response
            )
        );

        return $response;
    }
}
