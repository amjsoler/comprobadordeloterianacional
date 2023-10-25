<?php

namespace App\Models;

use App\Notifications\RecuperarCuenta;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RecuperarCuentaToken extends Model
{
    use HasFactory;

    protected $table = "recuperar_cuenta_tokens";

    ////////////////
    // RELACIONES //
    ////////////////

    public function usuario() : BelongsTo
    {
        return $this->belongsTo(User::class, "usuario", "id");
    }

    ///////////////////////
    // MÉTODOS ESTÁTICOS //
    ///////////////////////

    /**
     * Método para crear un nuevo token de recuperación de cuenta
     *
     * @param int $userID El usuario al que se asocia el token
     * @param Datetime $validez Hasta cuando es válido el token
     *
     * @return AccountVerifyToken Nuevo token creado para la verificación de la cuenta
     *  0: OK
     * -1: Excepción
     * -2: No se ha podido guardar el nuevo token
     */
    public static function crearTokenDeRecuperacionCuenta(int $userID, $validez)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al crearTokenDeRecuperacionCuenta de RecuperarCuentaToken",
                array(
                    "request: " => compact("userID", "validez")
                )
            );

            //Acción
            //Primero vacío los tokens del usuario para crear uno nuevo puesto un usuario solo puede tener un token
            RecuperarCuentaToken::where("usuario", $userID)->delete();

            //Ahora creo el nuevo token
            $nuevoRecuperarCuenta = new RecuperarCuentaToken();
            $nuevoRecuperarCuenta->usuario = $userID;
            $nuevoRecuperarCuenta->token = str_replace("/", "", Hash::make(now()));
            $nuevoRecuperarCuenta->valido_hasta = $validez;

            if($nuevoRecuperarCuenta->save()){
                $response["code"] = 0;
                $response["data"] = $nuevoRecuperarCuenta;
            }
            else{
                $response["code"] = -2;

                Log::error("Fallo al crear el token. No debería fallar",
                    array(
                        "request: " => compact("userID", "validez"),
                        "response: " => $response
                    )
                );
            }

            //Log de salida
            Log::debug("Saliendo del crearTokenDeRecuperacionCuenta de RecuperarCuentaToken",
                array(
                    "request: " => compact("userID", "validez"),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("userID", "validez"),
                    "response: " => $response
                )
            );
        }

        return $response;
    }

    /**
     * Método que devuelve el token pasado como parámetro en caso de que todavía sea válido
     *
     * @param string $token El token a buscar
     *
     * @return AccountVerifyToken
     *   0: OK
     *  -1: Excepción
     */
    public static function consultarToken(string $token)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al consultarToken de RecuperarCuentaToken",
                array(
                    "request: " => compact("token")
                )
            );

            //Acción
            $result = RecuperarCuentaToken::where("token", "=", $token)
                ->where("valido_hasta", ">", now())
                ->first();

            $response["code"] = 0;
            $response["data"] = $result;

            //Log de salida
            Log::debug("Saliendo del consultarToken de RecuperarCuentaToken",
                array(
                    "request: " => compact("token"),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("token"),
                    "response: " => $response
                )
            );
        }

        return $response;
    }
}
