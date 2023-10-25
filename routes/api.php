<?php

use App\Http\Controllers\ApiAuthentication;
use App\Http\Controllers\DecimoController;
use Illuminate\Support\Facades\Route;

//////////////////////////////////////
/////// RUTAS DE AUTENTICACIÓN ///////
//////////////////////////////////////

Route::post("/iniciar-sesion",
    [ApiAuthentication::class, "login"]);

Route::post("/registrarse",
    [ApiAuthentication::class, "register"]);

Route::post("/recuperar-cuenta",
    [
        ApiAuthentication::class, "recuperarCuenta"
    ]
);



////////////////////////////////
/////// RUTAS DE DÉCIMOS ///////
////////////////////////////////

//TODO: Falta el auth:sanctum
Route::get("/mis-decimos",
    [DecimoController::class, "verMisDecimos"]
);

//TODO: Falta el auth:sanctum
Route::post("/mis-decimos",
    [DecimoController::class, "crearDecimo"]
);

//TODO: Falta el auth:sanctum y el can policy
Route::put("/mis-decimos/{decimo}",
    [DecimoController::class, "modificarDecimo"]
);

//TODO: Falta el auth:sanctum y el can policy
Route::delete("/mis-decimos/{decimo}",
    [DecimoController::class, "eliminarDecimo"]
);




//TODO Comprobardecimo
