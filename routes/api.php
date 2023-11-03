<?php

use App\Http\Controllers\ApiAuthentication;
use App\Http\Controllers\DecimoController;
use Illuminate\Support\Facades\Route;

//////////////////////////////////////
/////// RUTAS DE AUTENTICACIÓN ///////
//////////////////////////////////////

Route::post("/iniciar-sesion",
    [ApiAuthentication::class, "login"]
)->middleware("guest");

Route::post("/registrarse",
    [ApiAuthentication::class, "register"]
)->middleware("guest");

Route::post("/recuperar-cuenta",
    [ApiAuthentication::class, "recuperarCuenta"]
);

Route::get("/verificar-cuenta",
    [ApiAuthentication::class, "mandarCorreoVerificacionCuenta"]
)->middleware("auth:sanctum");

////////////////////////////////
/////// RUTAS DE DÉCIMOS ///////
////////////////////////////////

Route::get("/mis-decimos",
    [DecimoController::class, "verMisDecimos"]
)->middleware(["auth:sanctum", "cuentaVerificada"]);

Route::post("/mis-decimos",
    [DecimoController::class, "crearDecimo"]
)->middleware(["auth:sanctum", "cuentaVerificada"]);

Route::put("/mis-decimos/{decimo}",
    [DecimoController::class, "modificarDecimo"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("update", "decimo");

Route::delete("/mis-decimos/{decimo}",
    [DecimoController::class, "eliminarDecimo"]
)->middleware(["auth:sanctum", "cuentaVerificada"])
    ->can("delete", "decimo");



/////////////////////////////////////
/////// RUTAS DE COMPROBACIÓN ///////
/////////////////////////////////////

Route::post("/comprobar-decimo",
    [DecimoController::class, "comprobarDecimo"]
);
