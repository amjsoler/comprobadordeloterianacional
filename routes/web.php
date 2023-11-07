<?php

use App\Http\Controllers\web\Authentication;
use App\Http\Controllers\web\SorteoController;
use App\Models\Sorteo;
use App\Models\User;
use App\Notifications\PruebaBorrar;
use App\Notifications\PruebaQueuedBorrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

///////////////////////////////
/////// RUTAS DE CUENTA ///////
///////////////////////////////

Route::get("verificar-cuenta/{token}",
    [Authentication::class, "verificarCuentaConToken"]
)->name("verificarcuentacontoken");

Route::get("recuperar-cuenta/{token}",
    [Authentication::class, "recuperarCuentaGet"]
)->name("recuperarcuentaget");

Route::post("recuperar-cuenta",
    [Authentication::class, "recuperarCuentaPost"]
)->name("recuperarcuentapost");

Route::get("/login", function(){
    return view("cuentaUsuario.login");
})->middleware(["guest"])
    ->name("login");

Route::post("/login", function(Request $request){
    if(Auth::attempt(array("email" => $request->get("email"), "password" => $request->get("password")))){
        return redirect(route("versorteos"));
    }else{
        return redirect()->back();
    }
})->middleware(["guest"]);


////////////////////////////////
/////// RUTAS DE SORTEOS ///////
////////////////////////////////

Route::get("/sorteos",
    [SorteoController::class, "verSorteos"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("delete", Sorteo::class)
    ->name("versorteos");

Route::post("/sorteos/crear",
    [SorteoController::class, "crearSorteo"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("delete", Sorteo::class)
    ->name("crearsorteo");

Route::get("/sorteos/{sorteo}/editar",
    [SorteoController::class, "editarSorteo"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("delete", Sorteo::class)
    ->name("editarsorteo");

Route::put("/sorteos/{sorteo}/modificar",
    [SorteoController::class, "modificarSorteo"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("delete", Sorteo::class)
    ->name("modificarsorteo");

Route::get("/sorteos/{sorteo}/eliminar",
    [SorteoController::class, "eliminarSorteo"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("delete", Sorteo::class)
    ->name("eliminarsorteo");

Route::get("/sorteos/{sorteo}/resultados", function(Sorteo $sorteo){
    return view("sorteos.verGuardarResultadosSorteo", compact("sorteo"));
}
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("delete", Sorteo::class)
    ->name("resultadossorteover");

Route::post("/sorteos/{sorteo}/resultados",
    [SorteoController::class, "guardarResultadosSorteo"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("delete", Sorteo::class)
    ->name("resultadossorteoguardar");

Route::get("prueba-correo", function(){
    User::where("email", "amjsoler@gmail.com")->first()->notify(new PruebaBorrar());
})->middleware("auth:sanctum", "cuentaVerificada")
->can("delete", Sorteo::class);

Route::get("prueba-queued-correo", function(){
    User::where("email", "amjsoler@gmail.com")->first()->notify(new PruebaQueuedBorrar());
})->middleware("auth:sanctum", "cuentaVerificada")
    ->can("delete", Sorteo::class);

