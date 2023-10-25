<?php

use App\Http\Controllers\ApiAuthentication;
use Illuminate\Support\Facades\Route;

//////////////////////////////////////
/////// RUTAS DE AUTENTICACIÓN ///////
//////////////////////////////////////

Route::post("/iniciar-sesion",
    [ApiAuthentication::class, "login"]);

Route::post("/registrarse",
    [ApiAuthentication::class, "register"]);

//TODO Verificación de correo electrónico

//TODO: Recuperar contraseña (este manda un mail con enlace que va a al form de la parte web)


//////////////////////////////////////
//////////////////////////////////////
//////////////////////////////////////

////////////////////////////////
/////// RUTAS DE DÉCIMOS ///////
////////////////////////////////

//Todas estas llevan auth:sanctum y el policy(excepto crear)
//TODO: Crear un nuevo décimo
//TODO: Editar un décimo
//TODO: Eliminar un décimo

////////////////////////////////
////////////////////////////////
////////////////////////////////

//TODO Comprobardecimo
