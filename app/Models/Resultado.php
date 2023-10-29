<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Resultado extends Model
{
    use HasFactory;

    protected $table = "resultados";

    //////////////////////
    ///// RELACIONES /////
    //////////////////////

    /**
     * El sorteo al que pertenece este resultado
     *
     * @return BelongsTo
     */
    public function sorteo(): BelongsTo
    {
        return $this->belongsTo(Sorteo::class, "usuario", "id");
    }

    /////////////////////////////
    ///// MÉTODOS ESTÁTICOS /////
    /////////////////////////////

    /**
     * Devuelve las fechas de resultados existentes en BD
     *
     * @return [fechas]
     *  0: OK
     * -1: Excepción
     * -2: Error en la consulta
     */
    public static function dameFechasExistentesResultadosBD()
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try {
            //Log de entrada
            Log::debug("Entrando al dameFechasExistentesResultadosBD de Resultado");

            $fechasResultadosExistentes = Sorteo::whereNotNull("resultados")
                ->get("fecha")
                ->asArray();

            if ($fechasResultadosExistentes) {
                $response["code"] = 0;
                $response["data"] = $fechasResultadosExistentes;
            } else {
                $response["code"] = -2;
            }

        } catch (Exception $e) {
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del dameFechasExistentesResultadosBD de Resultado",
            array(
                "response: " => $response
            )
        );

        return $response;
    }
}
