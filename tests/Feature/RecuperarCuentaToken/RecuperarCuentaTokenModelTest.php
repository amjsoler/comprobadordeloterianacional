<?php

namespace RecuperarCuentaToken;

use App\Models\RecuperarCuentaToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RecuperarCuentaTokenModelTest extends TestCase
{
    public function test_crear_token_de_recuperacion_cuenta(): void
    {
        //Primero intento crear un token de un usuario que no existe
        $assertableJsonResponse = AssertableJson::fromArray(
            RecuperarCuentaToken::crearTokenDeRecuperacionCuenta(9999999, Carbon::now()->addDay())
        );
        $assertableJsonResponse->where("code", -1);

        //Ahora creo un usuario sobre el que crear el token
        $userRand = User::factory()->create();

        $assertableJsonResponse = AssertableJson::fromArray(
            RecuperarCuentaToken::crearTokenDeRecuperacionCuenta($userRand->id, Carbon::now()->addDay())
        );
        $assertableJsonResponse->where("code", 0);
    }

    public function test_consultar_token()
    {
        //Primero intento consultar un token que no existe
        $assertableJsonResponse = AssertableJson::fromArray(
            RecuperarCuentaToken::consultarToken("invent")
        );
        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->where("data", null);

        //Ahora creo un user y un token
        $userRand = User::factory()->create();
        $accountVerifyTokenRand = RecuperarCuentaToken::crearTokenDeRecuperacionCuenta($userRand->id, now()->addDay());

        $assertableJsonResponse = AssertableJson::fromArray(
            RecuperarCuentaToken::consultarToken($accountVerifyTokenRand["data"]->token)
        );

        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->where("data.token", $accountVerifyTokenRand["data"]->token);

        //Ahora creo un user y un token CADUCADO
        $userRand = User::factory()->create();
        $accountVerifyTokenRand = RecuperarCuentaToken::crearTokenDeRecuperacionCuenta($userRand->id, now()->subDay());

        $assertableJsonResponse = AssertableJson::fromArray(
            RecuperarCuentaToken::consultarToken($accountVerifyTokenRand["data"]->token)
        );

        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->where("data", null);
    }
}
