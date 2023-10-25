<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountVerifyToken extends Model
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $table = "account_verify_tokens";


    ////////////////
    // RELACIONES //
    ////////////////

    public function usuario() : BelongsTo
    {
        return $this->belongsTo(User::class, "user", "id");
    }

    ///////////////////////
    // MÉTODOS ESTÁTICOS //
    ///////////////////////

    /**
     * Método para crear un nuevo token de verificación
     *
     * @param int $userID El usuario al que se asocia el token
     *
     * @return AccountVerifyToken Nuevo token creado para la verificación de la cuenta
     *  0: OK
     * -1: Excepción
     * -2: No se ha podido guardar el nuevo token
     */
    public static function crearTokenDeVerificación(int $userID, $validez)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al crearTokenDeVerificación de AccountVerifyToken",
                array(
                    "request: " => compact("userID", "validez")
                )
            );

            //Acción
            $nuevoAccountVerify = new AccountVerifyToken();
            $nuevoAccountVerify->usuario = $userID;
            $nuevoAccountVerify->token = str_replace("/", "", Hash::make(now()));
            $nuevoAccountVerify->valido_hasta = $validez;

            if($nuevoAccountVerify->save()){
                $response["code"] = 0;
                $response["data"] = $nuevoAccountVerify;
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
            Log::debug("Saliendo del crearTokenDeVerificación de AccountVerifyToken",
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
            Log::debug("Entrando al consultarToken de AccountVerifyToken",
                array(
                    "request: " => compact("token")
                )
            );

            //Acción
            $result = AccountVerifyToken::where("token", "=", $token)
                ->where("valido_hasta", ">", now())
                ->first();

            $response["code"] = 0;
            $response["data"] = $result;

            //Log de salida
            Log::debug("Saliendo del consultarToken de AccountVerifyToken",
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
