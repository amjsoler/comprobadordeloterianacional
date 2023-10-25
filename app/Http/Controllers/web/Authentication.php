<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecuperarCuentaPostRequest;
use App\Models\AccountVerifyToken;
use App\Models\RecuperarCuentaToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Authentication extends Controller
{
    /**
     * Método para verificar una cuenta de usuario dado un token
     *
     * @param string $token El token asociado a la cuenta de usuario
     *
     * @return null
     *   0: OK
     * -11: Excepción
     * -12: Token no encontrado o no valido
     * -13: Error al marcar la cuenta como verificada
     */
    public function verificarCuentaConToken(string $token)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al verificarCuentaConToken de Authentication",
            array(
                "request: " => compact("token")
            ));

            //Consulto el token y veo si todavía es válido
            $result = AccountVerifyToken::consultarToken($token);

            $accountVerifyResult = $result["data"];

            if($accountVerifyResult){
                $resultMarcarVerificacion = User::marcarCuentaVerificada($accountVerifyResult->usuario);

                if($resultMarcarVerificacion["code"] == 0){
                    $response["code"] = 0;
                    $response["status"] = 200;
                    $response["statusText"] = "ok";
                }else{
                    $response["code"] = -13;
                    $response["status"] = 400;
                    $response["statusText"] = "ko";
                }
            }else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                //El token no es válido, no se ha encontrado porque se lo ha inventado cambiando la url o se ha caducado
            }

            //Log de salida
            Log::debug("Saliendo del verificarCuentaConToken de Authentication",
                array(
                    "request: " => compact("token"),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -11;
            $response["status"] = 400;
            $response["statusText"] = "ko";

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("token"),
                    "repsonse: " => $response
                )
            );
        }

        return view("cuentaUsuario/verificarCuenta", compact("response"));
    }

    /**
     * Método que devuelve el formulario de cambio de contraseña en caso de que el token sea válido
     *
     * @param Request $request request que incluye el token
     *
     * @return
     */
    public function recuperarCuentaGet(string $token)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al recuperarCuentaGet de Authentication",
                array(
                    "request: " => compact("token")
                )
            );

            //Consulto el token y veo si todavía es válido
            $result = RecuperarCuentaToken::consultarToken($token);

            $recuperarCuentaResult = $result["data"];

            if($recuperarCuentaResult){
                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
                $response["data"] = $recuperarCuentaResult;
            }else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                //El token no es válido, no se ha encontrado porque se lo ha inventado cambiando la url o se ha caducado
            }

            //Log de salida
            Log::debug("Saliendo del verificarCuentaConToken de Authentication",
                array(
                    "request: " => $token,
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -11;
            $response["status"] = 400;
            $response["statusText"] = "ko";

            Log::error($e->getMessage(),
                array(
                    "request: " => $token,
                    "response: " => $response
                )
            );
        }

        return view("cuentaUsuario.recuperarCuenta", compact("response"));
    }

    /**
     * Método para guardar la nueva contraseña de usuario
     *
     * @param RecuperarCuentaPostRequest $request request que contiene la contraseña y el token
     *
     * @return void
     *   0: ok
     * -11: Excepción
     * -12: El token no es válido
     * -13: Fallo al guardar la nueva contraseña
     */
    public function recuperarCuentaPost(RecuperarCuentaPostRequest $request)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al recuperarCuentaPost de Authentication",
                array(
                    "request: " => $request->all()
                ));

            //Consulto el token y veo si todavía es válido
            $result = RecuperarCuentaToken::consultarToken($request->get("token"));

            $recuperarCuentaResult = $result["data"];

            if($recuperarCuentaResult){
                $resultMarcarVerificacion = User::guardarNuevoPass($recuperarCuentaResult->usuario, $request->get("password"));

                if($resultMarcarVerificacion["code"] == 0){
                    $response["code"] = 0;
                    $response["status"] = 200;
                    $response["statusText"] = "ok";
                }else{
                    $response["code"] = -13;
                    $response["status"] = 400;
                    $response["statusText"] = "ko";
                }
            }else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                //El token no es válido, no se ha encontrado porque se lo ha inventado cambiando la url o se ha caducado
            }

            //Log de salida
            Log::debug("Saliendo del recuperarCuentaPost de Authentication",
                array(
                    "request: " => $request->all(),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -11;
            $response["status"] = 400;
            $response["statusText"] = "ko";

            Log::error($e->getMessage(),
                array(
                    "request: " => $request->all(),
                    "repsonse: " => $response
                )
            );
        }

        return view("cuentaUsuario.recuperarCuentaResult", compact("response"));
    }
}
