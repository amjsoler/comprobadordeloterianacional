<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Decimo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "decimos";

    //////////////////////
    ///// RELACIONES /////
    //////////////////////

    /**
     * Usuario al que pertenece el décimo
     *
     * @return BelongsTo
     */
    public function usuarioPropietario() : BelongsTo
    {
        return $this->belongsTo(User::class, "usuario", "id");
    }

    public function sorteoPerteneciente() : BelongsTo
    {
        return $this->belongsTo(Sorteo::class, "sorteo", "id");
    }

    /////////////////////////////
    ///// MÉTODOS ESTÁTICOS /////
    /////////////////////////////

    /**
     * Método para leer los décimos del usuario pasado por paámetro
     *
     * @param User $user El usuario
     *
     * @return Decimo[] Los décimos del usuario
     *  0: OK
     * -1: Excepción
     */
    public static function dameMisDecimos(User $user)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al dameMisDecimos de Decimo",
                array(
                    "request: " => compact("user")
                )
            );

            //Acción
            $response["code"] = 0;
            $response["data"] = $user->decimos()->with("sorteoPerteneciente")->get();

            //Log de salida
            Log::debug("Saliendo del dameMisDecimos de Decimo",
                array(
                    "request: " => compact("user"),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("user"),
                    "response: " => $response
                )
            );
        }

        return $response;
    }

    /**
     * Método para crear un décimo
     *
     * @param int $usuario El usuario creador
     * @param string $numero El número del décimo
     * @param int $reintegro El reintegro
     * @param int|null $serie La serie
     * @param int|null $fraccion La fracción
     * @param int|null $cantidad La cantidad de décimos
     * @param int $sorteo El id del sorteo al que pertenece
     *
     * @return Decimo El décimo recién creado
     *  0: OK
     * -1: Excepción
     * -2: Error al guardar el décimo en BD
     */
    public static function crearDecimo
    (
        int $usuario,
        string $numero,
        int $reintegro,
        int|null $serie,
        int|null $fraccion,
        int|null $cantidad,
        int $sorteo
    )
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al crearDecimo de Decimo",
                array(
                    "request: " => compact(
                        "usuario",
                        "numero",
                        "reintegro",
                        "serie",
                        "fraccion",
                        "cantidad",
                        "sorteo"
                    )
                )
            );

            $nuevoDecimo = new Decimo();

            $nuevoDecimo->usuario = $usuario;
            $nuevoDecimo->numero = $numero;
            $nuevoDecimo->reintegro = $reintegro;

            if($serie){
                $nuevoDecimo->serie = $serie;
            }

            if($fraccion){
                $nuevoDecimo->fraccion = $fraccion;
            }

            if($cantidad){
                $nuevoDecimo->cantidad = $cantidad;
            }

            $nuevoDecimo->sorteo = $sorteo;

            if($nuevoDecimo->save()){
                $response["code"] = 0;
                $response["data"] = $nuevoDecimo;
            }else{
                $response["code"] = -2;
            }

            //Log de salida
            //Log de entrada
            Log::debug("Saliendo del crearDecimo de Decimo",
                array(
                    "request: " => compact(
                        "usuario",
                        "numero",
                        "reintegro",
                        "serie",
                        "fraccion",
                        "cantidad",
                        "sorteo"
                    ),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact(
                        "usuario",
                        "numero",
                        "reintegro",
                        "serie",
                        "fraccion",
                        "cantidad",
                        "sorteo"
                    ),
                    "response: " => $response
                )
            );
        }

        return $response;
    }

    /**
     * Método para modificar un décimo
     *
     * @param Decimo $decimo El décimo a modificar
     * @param string $numero El nuevo número
     * @param int $reintegro El nuevo reintegro
     * @param int|null $serie La nueva serie
     * @param int|null $fraccion La nueva fracción
     * @param int|null $cantidad La nueva cantidad
     * @param int $sorteo El numero sorteo
     *
     * @return Decimo El décimo modificado
     *  0: OK
     * -1: Excepción
     * -2: Error al almacenar los nuevos campos
     */
    public static function modificarDecimo(
        Decimo $decimo,
        string $numero,
        int $reintegro,
        int|null $serie,
        int|null $fraccion,
        int|null $cantidad,
        int $sorteo
    )
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al modificarDecimo de Decimo",
                array(
                    "request: " => compact(
                        "decimo",
                        "numero",
                        "reintegro",
                        "serie",
                        "fraccion",
                        "cantidad",
                        "sorteo"
                    )
                )
            );

            $decimo->numero = $numero;
            $decimo->reintegro = $reintegro;

            if($serie){
                $decimo->serie = $serie;
            }

            if($fraccion){
                $decimo->fraccion = $fraccion;
            }

            if($cantidad){
                $decimo->cantidad = $cantidad;
            }

            $decimo->sorteo = $sorteo;

            if($decimo->save()){
                $response["code"] = 0;
                $response["data"] = $decimo;
            }else{
                $response["code"] = -2;
            }

            //Log de salida
            Log::debug("Saliendo del modificarDecimo de Decimo",
                array(
                    "request: " => compact(
                        "decimo",
                        "numero",
                        "reintegro",
                        "serie",
                        "fraccion",
                        "cantidad",
                        "sorteo"
                    ),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact(
                        "decimo",
                        "numero",
                        "reintegro",
                        "serie",
                        "fraccion",
                        "cantidad",
                        "sorteo"
                    ),
                    "response: " => $response
                )
            );
        }

        return $response;
    }


    /**
     * Método de modelo para eliminar un décimo
     *
     * @return void
     *  0: OK
     * -1: Excepción
     * -2: Error al eliminar el décimo
     */
    public static function eliminarDecimo(Decimo $decimo)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al eliminarDecimo de Decimo",
                array(
                    "request: " => compact("decimo")
                )
            );

            if($decimo->delete()){
                $response["code"] = 0;
            }else{
                $response["code"] = -2;
            }

        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("decimo"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del eliminarDecimo de Decimo",
            array(
                "request: " => compact("decimo"),
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que devuelve los décimos que no se hayan comprobado todavía para el sorteo facilitado
     *
     * @param Sorteo $sorteo Sorteo sobre el que buscar décimos
     *
     * @return Decimo[] Los décimos del sorteo sin comprobar
     *
     *  0: ok
     * -1: Excepción
     */
    public static function dameDecimosSinComprobarDadoElSorteoWithUser(Sorteo $sorteo)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al dameDecimosSinComprobarDadoElSorteo de Decimo",
                array(
                    "request: " => $sorteo
                )
            );

            $decimos = Decimo::with("usuarioPropietario")
                ->where("premio" , null)
                ->where("sorteo", $sorteo->id)
                ->get();

            $response["code"] = 0;
            $response["data"] = $decimos;
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => $sorteo,
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del dameDecimosSinComprobarDadoElSorteo de Decimo",
            array(
                "request: " => $sorteo,
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que asigna el premio pasado por param al décimo también pasado
     *
     * @param Decimo $decimo Décimo al que asignar el premio
     * @param string $premioTotal Premio a asignar
     *
     * @return void
     *  0: ok
     * -1: Excepción
     * -2: Error al guardar el premio
     */
    public static function asignarPremio(Decimo $decimo, string $premioTotal)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al asignarPremio de Decimo",
                array(
                    "request: " => compact("decimo", "premioTotal")
                )
            );

            $decimo->premio = $premioTotal;

            if($decimo->save()){
                $response["code"] = 0;
            }else{
                $response["code"] = -2;
            }

        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("decimo", "premioTotal"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del asignarPremio de Decimo",
            array(
                "request: " => compact("decimo", "premioTotal"),
                "response: " => $response
            )
        );

        return $response;
    }
}
