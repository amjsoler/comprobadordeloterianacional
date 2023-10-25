<?php

use App\Http\Controllers\web\Authentication;
use Illuminate\Support\Facades\Route;

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

//TODO Crear un sorteo
//TODO Editar un sorteo
//TODO Eliminar un sorteo

//TODO Crear un resultado
//TODO Editar un resultado
//TODO Eliminar un resultado

//TODO Esnifar sorteos disponibles de varias fuentes
//TODO Esnifar resultados disponibles de varias fuentes
