<?php

namespace AccountVerifyToken;

use App\Models\AccountVerifyToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AccountVerifyTokenModelTest extends TestCase
{
    public function test_crear_token_de_verificacion(): void
    {
        //Primero intento crear un token de un usuario que no existe
        $assertableJsonResponse = AssertableJson::fromArray(
            AccountVerifyToken::crearTokenDeVerificacion(9999999, Carbon::now()->addDay()->toDateTime())
        );
        $assertableJsonResponse->where("code", -1);

        //Ahora creo un usuario sobre el que crear el token
        $userRand = User::factory()->create();

        $assertableJsonResponse = AssertableJson::fromArray(
            AccountVerifyToken::crearTokenDeVerificacion($userRand->id, Carbon::now()->addDay()->toDateTime())
        );
        $assertableJsonResponse->where("code", 0);
    }

    public function test_consultar_token()
    {
        //Primero intento consultar un token que no existe
        $assertableJsonResponse = AssertableJson::fromArray(
            AccountVerifyToken::consultarToken("invent")
        );
        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->where("data", null);

        //Ahora creo un user y un token
        $userRand = User::factory()->create();
        $accountVerifyTokenRand = AccountVerifyToken::crearTokenDeVerificacion($userRand->id, now()->addDay());

        $assertableJsonResponse = AssertableJson::fromArray(
            AccountVerifyToken::consultarToken($accountVerifyTokenRand["data"]->token)
        );

        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->where("data.token", $accountVerifyTokenRand["data"]->token);

        //Ahora creo un user y un token CADUCADO
        $userRand = User::factory()->create();
        $accountVerifyTokenRand = AccountVerifyToken::crearTokenDeVerificacion($userRand->id, now()->subDay());

        $assertableJsonResponse = AssertableJson::fromArray(
            AccountVerifyToken::consultarToken($accountVerifyTokenRand["data"]->token)
        );

        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->where("data", null);
    }
}
