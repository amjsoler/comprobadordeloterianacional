<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_dame_usuario_dado_correo()
    {
        //Correo inventado no existe
        $assertableJsonResponse = AssertableJson::fromArray(
            User::dameUsuarioDadoCorreo("invent@gmail.com")
        );
        $assertableJsonResponse->where("code", -2);

        //Ahora creo un user y busco sobre su correeo
        $userRand = User::factory()->create();
        $assertableJsonResponse = AssertableJson::fromArray(
            User::dameUsuarioDadoCorreo($userRand->email)
        );
        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->where("data.email", $userRand->email);
    }

    public function test_crear_nuevo_usuario()
    {
        $assertableJsonResponse = AssertableJson::fromArray(
            User::crearNuevoUsuario("invent", "invent@gmail.com", "password")
        );
        $assertableJsonResponse->where("code", 0);
        $this->assertDatabaseHas("users", ["name" => "invent"]);

        $assertableJsonResponse = AssertableJson::fromArray(
            User::crearNuevoUsuario("", "", "")
        );
        $assertableJsonResponse->where("code", 0);
        $this->assertDatabaseHas("users", ["name" => ""]);
    }

    public function test_marcar_cuenta_verificada()
    {
        $assertableJsonResponse = AssertableJson::fromArray(
            User::marcarCuentaVerificada(999999999999)
        );
        $assertableJsonResponse->where("code", -2);

        $userRand = User::factory()->create();
        $userRand->email_verified_at = null;
        $userRand->save();

        $this->assertEquals(null, $userRand->email_verified_at);

        $assertableJsonResponse = AssertableJson::fromArray(
            User::marcarCuentaVerificada($userRand->id)
        );
        $assertableJsonResponse->where("code", 0);
        $this->assertNotNull(User::find($userRand->id)->email_verified_at);
    }

    public function test_guardar_nuevo_pass()
    {
        $assertableJsonResponse = AssertableJson::fromArray(
            User::guardarNuevoPass(999999, "adsf")
        );
        $assertableJsonResponse->where("code", -2);

        $userRand = User::factory()->create();
        $assertableJsonResponse = AssertableJson::fromArray(
            User::guardarNuevoPass($userRand->id, "nuevapass")
        );
        $assertableJsonResponse->where("code", 0);
    }
}
