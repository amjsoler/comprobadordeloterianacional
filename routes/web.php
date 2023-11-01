<?php

use App\Http\Controllers\web\Authentication;
use App\Http\Controllers\web\SorteoController;
use App\Models\Sorteo;
use Illuminate\Support\Facades\Auth;
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

Route::get("/login", function(){
    return view("cuentaUsuario.login");
});

Route::post("/login", function($request){
    Auth::attempt($request->get("email"), $request->get("password"));
});


////////////////////////////////
/////// RUTAS DE SORTEOS ///////
////////////////////////////////

//TODO: Meter un auth:sanctum y un policy can
Route::get("/sorteos",
    [SorteoController::class, "verSorteos"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->name("versorteos");

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
