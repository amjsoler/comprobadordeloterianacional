<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("/login", function (){
    return response()->json(
        array(
            "access_token" => "3|04efeIXoqg1h4Tnkq8Nvm4jmqQJbTng7y9erYPcXce6a1069",
            "token_type" => "Bearer"
        ),
        200
    );
});

Route::post("/register", function() {
    return response()->json(
        array(
            "name" => "Jorge",
            "email" => "asdf2@asdf.com",
            "updated_at" => "2023-10-24T08:28:51.000000Z",
            "created_at" => "2023-10-24T08:28:51.000000Z",
            "id" => 104,
            "access_token" => "6|KSgrM5dvYsPZkHKqvDpoYMYDjGA3SY1Hs3k7AP8udf1ae51a",
            "token_type"=> "Bearer",
            "profile_photo_url"=> "https://ui-avatars.com/api/?name=J&color=7F9CF5&background=EBF4FF",
            "misdecimos" => array(
                array(
                    "id" => 100,
                    "sorteo" => 15,
                    "numero" => "56788",
                    "serie" => 3,
                    "fraccion" => 4,
                    "reintegro" => 3,
                    "cantidad" => 25
                ),
                array(
                    "id" => 567,
                    "sorteo" => 13,
                    "numero" => "09876",
                    "serie" => 3,
                    "fraccion" => 1,
                    "reintegro" => 3,
                    "cantidad" => 25
                ),
            )
        ),200
    );
});

Route::get("mis-decimos", function(){
    return response()->json(
        array(
            array(
                "id" => 100,
                "sorteo" => 15,
                "numero" => "56788",
                "serie" => 12,
                "fraccion" => 4,
                "reintegro" => 3,
                "cantidad" => 25
            ),
            array(
                "id" => 567,
                "sorteo" => 13,
                "numero" => "09876",
                "serie" => 2,
                "fraccion" => 1,
                "reintegro" => 3,
                "cantidad" => 25
                ),
        ),200
    );
});

Route::post("recuperar-contrasena", function (){
   return response()->json(
       "", 200
   );
});

Route::post("mis-decimos/crear", function(){
   return response()->json(
       array(
           "id" => 567,
           "sorteo" => 13,
           "numero" => "09876",
           "serie" => 2,
           "fraccion" => 1,
           "reintegro" => 3,
           "cantidad" => 25
       ), 200
   );
});

Route::delete("/mis-decimos/{decimo}", function(){
   return response()->json(
      "", 200
   );
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
