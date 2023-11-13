<?php

use App\Http\Controllers\ApiAuthentication;
use App\Http\Controllers\DecimoController;
use App\Http\Controllers\web\SorteoController;
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

Route::post("/cambiar-contrasena",
    [ApiAuthentication::class, "cambiarContrasena"]
)->middleware("auth:sanctum", "cuentaVerificada");

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

Route::get("/archivar-decimos/{sorteo}",
    [DecimoController::class, "archivarDecimosDeSorteo"]
)->middleware(["auth:sanctum", "cuentaVerificada"]);

////////////////////////////////
/////// RUTAS DE SORTEOS ///////
////////////////////////////////

Route::get("/sorteos-disponibles",
    [SorteoController::class, "dameSorteosDisponibles"]
)->middleware(["auth:sanctum", "cuentaVerificada"]);

Route::get("/ultimos-resultados",
    [SorteoController::class, "dameUltimosResultados"]
);

Route::post("/id-sorteo-dada-fecha",
    [SorteoController::class, "dameIdSorteoDadaLaFecha"]
);

/////////////////////////////////////
/////// RUTAS DE COMPROBACIÓN ///////
/////////////////////////////////////

Route::post("/comprobar-decimo",
    [DecimoController::class, "comprobarDecimo"]
);
