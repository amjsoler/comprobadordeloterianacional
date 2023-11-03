<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\ComprobarDecimoFormRequest;
use App\Http\Requests\CrearDecimoFormRequest;
use App\Http\Requests\ModificarDecimoFormRequest;
use App\Models\Decimo;
use App\Models\Sorteo;
use Exception;
use Illuminate\Support\Facades\Log;

class DecimoController extends Controller
{
    /**
     * Método que devuelve los décimos del usuario logueado
     *
     * @return Decimo[] El listado de décimos del usuario
     *   0: OK
     * -11: Excepción
     * -12: Error al leer los décimos del usuario en el modelo
     */
    public function verMisDecimos()
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al verMisDecimos de DecimoController",
                array
                (
                    "usuarioID: " => auth()->user()->id
                )
            );

            //Creo el nuevo token
            $resultVerDecimos = Decimo::dameMisDecimos(auth()->user());

            if($resultVerDecimos["code"] == 0){
                $misDecimos = $resultVerDecimos["data"];

                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
                $response["data"] = $misDecimos;
            }else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("Esto es raro que falle",
                    array
                    (
                        "usuarioID: " => auth()->user()->id,
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
                array
                (
                    "usuarioID: " => auth()->user()->id,
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del verMisDecimos de DecimoController",
            array
            (
                "usuarioID: " => auth()->user()->id,
                "response: " => $response
            )
        );

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }

    /**
     * Método para crear un décimo
     *
     * @param CrearDecimoFormRequest $request La información del décimo
     *
     * @return Decimo El décimo creado
     *   0: OK
     * -11: Excepción
     * -12: Error en la creación del décimo
     */
    public function crearDecimo(CrearDecimoFormRequest $request)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al crearDecimo de DecimoController",
                array(
                    "usuarioID: " => auth()->user()->id,
                    "request: " => $request->all()
                )
            );

            //Creo el nuevo token
            $resultVerDecimos = Decimo::crearDecimo(
                auth()->user()->id,
                $request->get("numero"),
                $request->get("reintegro"),
                $request->get("serie"),
                $request->get("fraccion"),
                $request->get("cantidad"),
                $request->get("sorteo"),
            );

            if($resultVerDecimos["code"] == 0){
                $misDecimos = $resultVerDecimos["data"];

                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
                $response["data"] = $misDecimos;
            }else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("Esto es raro que falle",
                    array(
                        "usuarioID: " => auth()->user()->id,
                        "request: " => $request->all(),
                        "response: " => $response
                    )
                );
            }

            //Log de salida
            Log::debug("Saliendo del crearDecimo de DecimoController",
                array(
                    "usuarioID: " => auth()->user()->id,
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
                    "usuarioID: " => auth()->user()->id,
                    "request: " => $request->all(),
                    "response: " => $response
                )
            );
        }

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }

    public function modificarDecimo(Decimo $decimo, ModificarDecimoFormRequest $request)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al modificarDecimo de DecimoController",
                array(
                    "usuarioID: " => auth()->user()->id,
                    "request: " => $request->all()
                )
            );

            //Creo el nuevo token
            $resultModificarDecimos = Decimo::modificarDecimo(
                $decimo,
                $request->get("numero"),
                $request->get("reintegro"),
                $request->get("serie"),
                $request->get("fraccion"),
                $request->get("cantidad"),
                $request->get("sorteo")
            );

            if($resultModificarDecimos["code"] == 0){
                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
                $response["data"] = $resultModificarDecimos["data"];
            }else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("No debería fallar la modificación del décimo si el validador funciona correctamente",
                    array(
                        "usuarioID: " => auth()->user()->id,
                        "request: " => $request->all(),
                        "response: " => $response
                    )
                );
            }

            //Log de salida
            Log::debug("Saliendo del modificarDecimo de DecimoController",
                array(
                    "usuarioID: " => auth()->user()->id,
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
                    "usuarioID: " => auth()->user()->id,
                    "request: " => $request->all(),
                    "response: " => $response
                )
            );
        }

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }

    /**
     * Método para eliminar un décimo de un usuario
     *
     * @param Decimo $decimo El décimo a eliminar
     *
     * @return void
     *   0: OK
     * -11: Excepción
     * -12: Error al eliminar el décimo
     */
    public function eliminarDecimo(Decimo $decimo)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al eliminarDecimo de DecimoController",
                array(
                    "usuarioID: " => auth()->user()->id,
                    "request: " => compact("decimo")
                )
            );

            //Creo el nuevo token
            $resultEliminarDecimos = Decimo::eliminarDecimo($decimo);

            if($resultEliminarDecimos["code"] == 0){
                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
            }else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("No debería fallar la eliminación del décimo",
                    array(
                        "usuarioID: " => auth()->user()->id,
                        "request: " => compact("decimo"),
                        "response: " => $response
                    )
                );
            }

            //Log de salida
            Log::debug("Saliendo del eliminarDecimo de DecimoController",
                array(
                    "usuarioID: " => auth()->user()->id,
                    "request: " => compact("decimo"),
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
                    "usuarioID: " => auth()->user()->id,
                    "request: " => compact("decimo"),
                    "response: " => $response
                )
            );
        }

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }

    /**
     * Método que se usa para comprobar si el décimo pasado por parametro está premiado
     *
     * @param ComprobarDecimoFormRequest $request Los datos de la apuesta y el sorteo
     *
     * @return {premioTotal, premiosObtenidos=>[{premio, cantidad}]}
     *   0: OK
     * -11: Excepción
     * -12: Error al leer los resultados
     * -13: Error al comprobar el décimo
     */
    public function comprobarDecimo(ComprobarDecimoFormRequest $request)
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al comprobarDecimo de DecimoController",
                array(
                    "request: " => $request->all()
                )
            );

            //Primero compruebo si están ya disponibles los resultado
            $resultResultadosSorteo = Sorteo::dameResultadosDadoElSorteo($request->get("sorteo"));

            if($resultResultadosSorteo["code"] == 0){
                $resultadosSorteo = $resultResultadosSorteo["data"];

                //Si hay resultados hay que comprobar los datos pasados desde cliente
                $resultComprobarPremios = Helpers::comprobarDecimo($resultadosSorteo,
                    $request->get("numero"),
                    $request->get("reintegro"),
                    $request->get("serie"),
                    $request->get("fraccion")
                );

                if($resultComprobarPremios["code"] == 0){
                    $response["code"] = 0;
                    $response["status"] = 200;
                    $response["statusText"] = "ok";
                    $response["data"] = $resultComprobarPremios["data"];
                }else{
                    $response["code"] = -13;
                    $response["status"] = 400;
                    $response["statusText"] = "ko";
                }
            }else if($resultResultadosSorteo["code"] == -2){
                //No se han encontrado resultados
                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
                $response["data"] = "";
            }
            else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("No debería fallar la consulta de resultados",
                    array(
                        "request: " => $request->all(),
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
                    "request: " => $request->all(),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del comprobarDecimo de DecimoController",
            array(
                "request: " => $request->all(),
                "response: " => $response
            )
        );

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }
}
