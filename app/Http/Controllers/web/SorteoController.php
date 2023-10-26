<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sorteo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

        return redirect(route("versorteos"));
    }
}
