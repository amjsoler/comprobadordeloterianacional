<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjustesCuentaFormRequest;
use App\Http\Requests\EnviarSugerenciaFormRequest;
use App\Models\User;
use App\Notifications\EnviarSugerenciaAlAdministrador;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    public function enviarSugerencia(EnviarSugerenciaFormRequest $request)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al enviarSugerencia de UserController",
                array(
                    "userID: " => auth()->user()->id,
                    "request: " => $request->all()
                )
            );

            Notification::send(User::where("email", env("ADMIN_AUTORIZADO"))->first(), new EnviarSugerenciaAlAdministrador($request->get("texto"), auth()->user()->email));

            $response["status"] = 200;
            $response["code"] = 0;
        }
        catch(Exception $e){
            $response["code"] = -11;
            $response["status"] = 400;
            $response["statusText"] = "ko";

            Log::error($e->getMessage(),
                array(
                    "userID: " => auth()->user()->id,
                    "request: " => $request->all(),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del enviarSugerencia de UserController",
            array(
                "userID: " => auth()->user()->id,
                "request: " => $request->all(),
                "response: " => $response
            )
        );

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }

    /**
     * Método para eliminar la cuenta de usuario del usuario logueado
     *
     * @return void
     *   0: ok
     * -11: Excepción
     * -12: Fallo en el método de eliminación del modelo
     */
    public function eliminarCuenta()
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al eliminarCuenta de UserController",
                array(
                    "userID: " => auth()->user()->id,
                )
            );

            //Primero compruebo si están ya disponibles los resultado
            $resultEliminarCuenta = User::eliminarCuenta(auth()->user()->id);

            if($resultEliminarCuenta["code"] == 0){
                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
            }
            else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("No debería fallar una consulta de eliminación",
                    array(
                        "userID: " => auth()->user()->id,
                        "response: " => $response
                    )
                );
            }

        }
        catch(Exception $e){
            $response["code"] = -11;
            $response["status"] = 400;
            $response["statusText"] = "ko";

            Log::error($e->getMessage(),
                array(
                    "userID: " => auth()->user()->id,
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del eliminarCuenta de UserController",
            array(
                "userID: " => auth()->user()->id,
                "response: " => $response
            )
        );

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }

    /**
     * Método que almacena la nueva configuración de usuario con las alertas
     *
     * @param AjustesCuentaFormRequest $request Los parámetros de configuración
     *
     * @return void
     *  0: OK
     * -11: Excepción
     * -12: Fallo en el modelo
     */
    public function guardarAjustesCuentaUsuario(AjustesCuentaFormRequest $request)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al guardarAjustesCuentaUsuario de UserController",
                array(
                    "userID: " => auth()->user()->id,
                    "request: " => compact("request")
                )
            );

            //Primero compruebo si están ya disponibles los resultado
            $resultGuardarAjustes = User::guardarAjustesCuentaUsuario(
                auth()->user()->id,
                $request->get("alertasporcorreo"),
                $request->get("alertaspornotificacion"),
            );

            if($resultGuardarAjustes["code"] == 0){
                $resultGuardarAjustes = $resultGuardarAjustes["data"];

                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
                $response["data"] = $resultGuardarAjustes;
            }
            else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("No debería fallar una consulta de guardado",
                    array(
                        "userID: " => auth()->user()->id,
                        "request: " => compact("request"),
                        "response: " => $response
                    )
                );
            }

        }
        catch(Exception $e){
            $response["code"] = -11;
            $response["status"] = 400;
            $response["statusText"] = "ko";

            Log::error($e->getMessage(),
                array(
                    "userID: " => auth()->user()->id,
                    "request: " => compact("request"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del guardarAjustesCuentaUsuario de UserController",
            array(
                "userID: " => auth()->user()->id,
                "request: " => compact("request"),
                "response: " => $response
            )
        );

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }

    public function leerAjustesCuentaUsuario()
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al leerAjustesCuentaUsuario de UserController",
                array(
                    "userID: " => auth()->user()->id,
                )
            );

            //Primero compruebo si están ya disponibles los resultado
            $resultLeerAjustes = User::leerAjustesCuentaUsuario(auth()->user()->id);

            if($resultLeerAjustes["code"] == 0){
                $resultLeerAjustes = $resultLeerAjustes["data"];

                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
                $response["data"] = $resultLeerAjustes;
            }
            else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("No debería fallar una consulta de lectura",
                    array(
                        "userID: " => auth()->user()->id,
                        "response: " => $response
                    )
                );
            }

        }
        catch(Exception $e){
            $response["code"] = -11;
            $response["status"] = 400;
            $response["statusText"] = "ko";

            Log::error($e->getMessage(),
                array(
                    "userID: " => auth()->user()->id,
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del leerAjustesCuentaUsuario de UserController",
            array(
                "userID: " => auth()->user()->id,
                "response: " => $response
            )
        );

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }
}
