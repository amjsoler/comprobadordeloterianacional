<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Resultado;
use App\Models\Sorteo;
use Illuminate\Http\Request;

class ResultadoController extends Controller
{
    public function verResultados()
    {
        $resultados = Resultado::all();
        $sorteos = Sorteo::all();

        return view("resultados.verResultados", compact("resultados", "sorteos"));
    }

    public function crearResultado(Request $request)
    {
        $resultado = new Resultado();
        $resultado->numero = $request->get("numero");
        $resultado->reintegro = $request->get("reintegro");
        $resultado->serie = $request->get("serie");
        $resultado->fraccion = $request->get("fraccion");
        $resultado->sorteo = $request->get("sorteo");

        $resultado->save();

        return redirect(route("verresultados"));
    }

    public function editarResultado(Resultado $resultado)
    {
        $sorteos = Sorteo::all();

        return view("resultados.editarResultado", compact("resultado", "sorteos"));
    }

    public function modificarResultado(Request $request, Resultado $resultado)
    {
        $resultado->numero = $request->get("numero");
        $resultado->reintegro = $request->get("reintegro");
        $resultado->serie = $request->get("serie");
        $resultado->fraccion = $request->get("fraccion");
        $resultado->sorteo = $request->get("sorteo");

        $resultado->save();

        return redirect(route("verresultados"));
    }

    public function eliminarResultado(Resultado $resultado)
    {
        $resultado->delete();

        return redirect(route("verresultados"));
    }

    public function esnifarResultados()
    {

    }
}
