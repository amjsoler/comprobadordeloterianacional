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
                $response["data"] = $resultados->resultados;
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

    /**
     * Función que devuelve la lista de sorteos disponibles
     *
     * @return [Sorteo]
     *  0: OK
     * -1: Excepción
     */
    public static function sorteosDisponibles()
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try {
            //Log de entrada
            Log::debug("Entrando al sorteosDisponibles de Sorteo");

            $sorteosDisponibles = Sorteo::whereNull("resultados")
                ->whereDate("fecha", ">=", now()->toDate())
                ->get();

            $response["code"] = 0;
            $response["data"] = $sorteosDisponibles;

        } catch (Exception $e) {
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del sorteosDisponibles de Resultado",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Devuelve los últimos X sorteos que tengan resultados, siendo X el número max de sorteos con resultados a devolver
     *
     * @param int $cantidadSorteos El número máximo de sorteos con resultado a devolver
     *
     * @return [Sorteo] Los sorteos con resultados
     *
     *  0: OK
     * -1: Excepción
     * -2: Error al buscar los sorteos
     */
    public static function dameUltimosSorteosConResultado(int $cantidadSorteos)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try {
            //Log de entrada
            Log::debug("Entrando al dameUltimosSorteosConResultado de Sorteo");

            $sorteosConResultadoDisponibles = Sorteo::whereNotNull("resultados")
                ->where("fecha", "<=", now())
                ->orderBy("fecha", "desc")
                ->limit($cantidadSorteos)
                ->get();

            $response["code"] = 0;
            $response["data"] = $sorteosConResultadoDisponibles;

        } catch (Exception $e) {
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del dameUltimosSorteosConResultado de Resultado",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que devuelve el id del sorteo pasado como parámetro equivalente a la fecha pasada
     *
     * @param String $fechaSorteo fecha del sorteo sobre el que buscar
     *
     * @return int El identificador del sorteo
     *  0: OK
     * -1: Excepción
     * -2: Sorteo no encontrado
     */
    public static function dameIdSorteoDadaFecha($fechaSorteo)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try {
            //Log de entrada
            Log::debug("Entrando al dameIdSorteoDadaFecha de Sorteo",
            array(
                "request: " => $fechaSorteo
            ));

            $sorteoCorrespondienteALaFecha = Sorteo::where("fecha", "=", $fechaSorteo)
                ->first();

            if($sorteoCorrespondienteALaFecha){
                $response["code"] = 0;
                $response["data"] = $sorteoCorrespondienteALaFecha;
            }else{
                $response["code"] = -2;
            }

        } catch (Exception $e) {
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => $fechaSorteo,
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del dameIdSorteoDadaFecha de Resultado",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que archiva/softdelete los décimos del sorteo y usuario pasados por parámetro
     *
     * @param int $userId El usuario propietario de los décimos
     * @param int $sorteoId El sorteo del cual archivar décimos
     *
     * @return [int]
     *  0: OK
     * -1: Excepción
     * -2: Error al borrar los décimos
     */
    public static function archivarDecimosSorteoPasado(int $userId, int $sorteoId)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try {
            //Log de entrada
            Log::debug("Entrando al archivarDecimosSorteoPasado de Sorteo",
                array(
                    "request: " => compact("userId", "sorteoId")
                )
            );

            $resultBorrado = Decimo::where("usuario", $userId)
                ->where("sorteo", $sorteoId);

            $auxIds = $resultBorrado->get("id")->toArray();

            if($resultBorrado->delete()){
                $response["code"] = 0;
                $response["data"] = $auxIds;
            }else{
                $response["code"] = -2;
            }

        } catch (Exception $e) {
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("userId", "sorteoId"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del archivarDecimosSorteoPasado de Resultado",
            array(
                "request: " => compact("userId", "sorteoId"),
                "response: " => $response
            )
        );

        return $response;
    }
}
