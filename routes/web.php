<?php

use App\Helpers\Helpers;
use App\Http\Controllers\web\Authentication;
use App\Http\Controllers\web\ResultadoController;
use App\Http\Controllers\web\SorteoController;
use App\Models\Sorteo;
use Illuminate\Support\Facades\Route;

///////////////////////////////
/////// RUTAS DE CUENTA ///////
///////////////////////////////

Route::get("verificar-cuenta/{token}",
    [
        Authentication::class,
        "verificarCuentaConToken"
    ]
)->name("verificarcuentacontoken");

Route::get("recuperar-cuenta/{token}",
    [Authentication::class, "recuperarCuentaGet"]
)->name("recuperarcuentaget");

Route::post("recuperar-cuenta",
    [Authentication::class, "recuperarCuentaPost"]
)->name("recuperarcuentapost");



////////////////////////////////
/////// RUTAS DE SORTEOS ///////
////////////////////////////////

//TODO: Meter un auth:sanctum y un policy can
Route::get("/sorteos",
    [SorteoController::class, "verSorteos"]
)->name("versorteos");

//TODO: Meter un auth:sanctum y un policy can
Route::post("/sorteos/crear",
    [SorteoController::class, "crearSorteo"]
)->name("crearsorteo");

//TODO: Meter un auth:sanctum y un policy can
Route::get("/sorteos/{sorteo}/editar",
    [SorteoController::class, "editarSorteo"]
)->name("editarsorteo");

//TODO: Meter un auth:sanctum y un policy can
Route::put("/sorteos/{sorteo}/modificar",
    [SorteoController::class, "modificarSorteo"]
)->name("modificarsorteo");

//TODO: Meter un auth:sanctum y un policy can
Route::get("/sorteos/{sorteo}/eliminar",
    [SorteoController::class, "eliminarSorteo"]
)->name("eliminarsorteo");

Route::get("/sorteos/{sorteo}/resultados", function(Sorteo $sorteo){
    return view("sorteos.verGuardarResultadosSorteo", compact("sorteo"));
}
)->name("resultadossorteover");

Route::post("/sorteos/{sorteo}/resultados",
    [SorteoController::class, "guardarResultadosSorteo"]
)->name("resultadossorteoguardar");


///////////////////////////////////
/////// RUTAS DE RESULTADOS ///////
///////////////////////////////////

//TODO: Meter un auth:sanctum y un policy can
Route::get("/resultados",
    [ResultadoController::class, "verResultados"]
)->name("verresultados");

//TODO: Meter un auth:sanctum y un policy can
Route::post("/resultados/crear",
    [ResultadoController::class, "crearResultado"]
)->name("crearresultado");

//TODO: Meter un auth:sanctum y un policy can
Route::get("/resultados/{resultado}/editar",
    [ResultadoController::class, "editarResultado"]
)->name("editarresultado");

//TODO: Meter un auth:sanctum y un policy can
Route::put("/resultados/{resultado}/modificar",
    [ResultadoController::class, "modificarResultado"]
)->name("modificarresultado");

//TODO: Meter un auth:sanctum y un policy can
Route::get("/resultados/{resultado}/eliminar",
    [ResultadoController::class, "eliminarResultado"]
)->name("eliminarresultado");

//TODO Esnifar sorteos disponibles de varias fuentes
//TODO Esnifar resultados disponibles de varias fuentes



Route::get("/esnifeteo-resultados", function(){
        Helpers::esnifarYGuardarResultadosDisponibles();
    }
);
