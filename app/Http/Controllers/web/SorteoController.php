<?php

namespace App\Http\Controllers\web;

use App\Events\NuevosResultadosGuardados;
use App\Http\Controllers\Controller;
use App\Models\Decimo;
use App\Models\Sorteo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SorteoController extends Controller
{
    public function verSorteos()
    {
        $sorteos = Sorteo::all();

        return view("sorteos.verSorteos", compact("sorteos"));
    }

    public function crearSorteo(Request $request)
    {
        $sorteo = new Sorteo();
        $sorteo->nombre = $request->get("nombre");
        $sorteo->fecha = $request->get("fecha");
        $sorteo->numero_sorteo = $request->get("numero_sorteo");

        $sorteo->save();

        return redirect(route("versorteos"));
    }

    public function editarSorteo(Sorteo $sorteo)
    {
        return view("sorteos.editarSorteo", compact("sorteo"));
    }

    public function modificarSorteo(Request $request, Sorteo $sorteo)
    {
        $sorteo->nombre = $request->nombre;
        $sorteo->fecha = $request->fecha;
        $sorteo->numero_sorteo = $request->numero_sorteo;
        $sorteo->save();

        return redirect(route("versorteos"));
    }

    public function eliminarSorteo(Sorteo $sorteo)
    {
        $sorteo->delete();

        return redirect(route("versorteos"));
    }

    public function guardarResultadosSorteo(Sorteo $sorteo, Request $request)
    {
        $resultados = $request->get("resultados");

        $sorteo->resultados = $resultados;
        $sorteo->save();

        //Lanzo evento de guardar resultados
        NuevosResultadosGuardados::dispatch($sorteo);

        return redirect(route("versorteos"));
    }

    /**
     * Devuelve los sorteos activos que haya en BD
     *
     * @return [Sorteo] La colección de sorteos disponibles
     *   0: OK
     * -11: Excepción
     * -12: No se ha podido leer la lista de sorteos
     */
    public function dameSorteosDisponibles()
    {
        $response = [
            "status" => "",
            "code" => "",
            "statusText" => "",
            "data" => []
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al dameSorteosDisponibles de SorteoController",
                array(
                    "usuarioID: " => auth()->user()->id,
                )
            );

            //Creo el nuevo token
            $resultSorteosDisponibles = Sorteo::sorteosDisponibles();

            if($resultSorteosDisponibles["code"] == 0){
                $response["code"] = 0;
                $response["status"] = 200;
                $response["statusText"] = "ok";
                $response["data"] = $resultSorteosDisponibles["data"];
            }else{
                $response["code"] = -12;
                $response["status"] = 400;
                $response["statusText"] = "ko";

                Log::error("No debería fallar la consulta de sorteos",
                    array(
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
                array(
                    "usuarioID: " => auth()->user()->id,
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del dameSorteosDisponibles de SorteoController",
            array(
                "usuarioID: " => auth()->user()->id,
                "response: " => $response
            )
        );

        return response()->json(
            $response["data"],
            $response["status"]
        );
    }
}
