<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjustesCuentaFormRequest;
use App\Models\Sorteo;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
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
                $request->get("alertas_por_correo"),
                $request->get("alertas_por_notificacion"),
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
}
