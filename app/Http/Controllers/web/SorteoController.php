<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sorteo;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

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

    public function esnifarSorteos()
    {
        $sorteosObtenidos = [];

        //Sacamos del env el listado de urls desde donde se esnifará
        $urls = explode(";", env("URLS_ESNIFAR_SORTEOS"));

        //iteramos por cada url
        foreach($urls as $url){
            $sorteosObtenidosIndiv = [];

            //Hacemos la petición y creamos el objeto DOM para movernos
            $contenido = Http::get($url)->body();
            $contenidoHTML = new DOMDocument;
            libxml_use_internal_errors(true);
            $contenidoHTML->loadHTML($contenido);

            //Buscamos el desplegable de sorteos y leemos los options que tiene
            $optionsSorteos = $contenidoHTML->getElementById("loteria_desplegable")
                ->getElementsByTagName("option");

            //Iteramos por los options (cada sorteo)
            for($i=0;$i<$optionsSorteos->count();$i++){
                //extraigo la cadena del nombre y fecha y el número de sorteo
                $cadenaSorteo = $optionsSorteos->item($i)->textContent;
                $valueOption = $optionsSorteos->item($i)->attributes->getNamedItem("value")->nodeValue;

                //Extraigo el número de sorteo de la cadena; 2023102
                //Quito el año de delante y me quedo con el nº de sorteo
                $numSorteo = substr($valueOption, 4);

                //Separo la cadena con el nombre y fecha por espacios para así coger lo que quiera
                $cadenaSorteosSplit = explode(" ", $cadenaSorteo);

                //Extraigo la fecha
                $fechaux = Carbon::createFromFormat("d/m/Y", $cadenaSorteosSplit[count($cadenaSorteosSplit)-1]);
                $fechaSorteo = $fechaux->format("Y-m-d");

                //Quito el último elemento del array que es la fecha y quedarme así con el nombre del sorteo
                array_pop($cadenaSorteosSplit);
                $nombreSorteo = implode(" ", $cadenaSorteosSplit);

                //Guardo los datos en el array para compararlo después con las otras fuentes
                array_push($sorteosObtenidosIndiv, (object)["nombre" => $nombreSorteo, "fecha" => $fechaSorteo, "numero_sorteo" => $numSorteo]);
            }

            array_push($sorteosObtenidos, $sorteosObtenidosIndiv);
            array_push($sorteosObtenidos, array_reverse($sorteosObtenidosIndiv));
        }

        //TODO: Por ahora se están teniendo en cuenta los resultados del primero
        //TODO: Falta implementar la comparación

        //Una vez tengo el array definitivo, comparo contra BD para insertar los nuevos
        $sorteosDefinitivos = $sorteosObtenidos[0]; //TODO:

        //Leo de la bd los sorteos que hayan con las fechas de arriba
        $sorteosExistentes = Sorteo::whereIn("fecha", array_column($sorteosDefinitivos, "fecha"))->get("fecha");

        $noInsertarSorteos = [];

        //Ahora vamos a excluír los sorteos que ya estén en BD
        foreach($sorteosExistentes as $sorteoExistente){
            $encontrado = array_filter($sorteosDefinitivos, function($obj) use ($sorteoExistente){
                if($obj->fecha == $sorteoExistente->fecha){
                    return true;
                }else{
                    return false;
                }
            });

            $noInsertarSorteos = array_merge($encontrado, $noInsertarSorteos);
        }

        foreach($sorteosDefinitivos as $key => $sorteosDefinitivo){
            $encontrado = false;
            foreach($noInsertarSorteos as $noInsertarSorteo){
                if($sorteosDefinitivo->fecha == $noInsertarSorteo->fecha){
                    $encontrado = true;
                }
            }

            if($encontrado){
                unset($sorteosDefinitivos[$key]);
            }
        }

        foreach($sorteosDefinitivos as $insertar){
            $sort = new Sorteo();
            $sort->nombre = $insertar->nombre;
            $sort->fecha = $insertar->fecha;
            $sort->numero_sorteo = $insertar->numero_sorteo;
            $sort->save();
        }
    }
}
