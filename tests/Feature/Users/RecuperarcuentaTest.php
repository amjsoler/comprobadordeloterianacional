<?php

namespace Tests\Feature\Users;

use App\Models\RecuperarCuentaToken;
use App\Models\User;
use App\Notifications\RecuperarCuenta;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RecuperarcuentaTest extends TestCase
{
    public function test_recuperar_cuenta_ko_validacion_error(): void
    {
        $response = $this->post("/recuperar-cuenta", array());
        $response->assertInvalid(["password"]);

        $response = $this->post("/recuperar-cuenta",
            array(
                "password" => "password"
            )
        );
        $response->assertInvalid(["password"]);

        $response = $this->post("/recuperar-cuenta",
            array(
                "password" => "password",
                "password_confirmation" => "diferente"
            )
        );
        $response->assertInvalid(["password"]);
    }

    public function test_recuperar_cuenta_get_web()
    {
        $response = $this->get("/recuperar-cuenta");
        $response->assertStatus(405);

        $response = $this->get("/recuperar-cuenta/");
        $response->assertStatus(405);

        $response = $this->get("recuperar-cuenta/inventado");
        $response->assertStatus(200);
        $this->assertEquals(-12, $response->viewData("response")["code"]);

        $userRand = User::factory()->create();
        $token = RecuperarCuentaToken::crearTokenDeRecuperacionCuenta($userRand->id, now()->subDay());

        $response = $this->get("recuperar-cuenta/" . $token["data"]->token);
        $response->assertStatus(200);
        $this->assertEquals(-12, $response->viewData("response")["code"]);

        $token = RecuperarCuentaToken::crearTokenDeRecuperacionCuenta($userRand->id, now()->addDay());

        $response = $this->get("recuperar-cuenta/" . $token["data"]->token);
        $response->assertStatus(200);
        $this->assertEquals(0, $response->viewData("response")["code"]);
    }

    public function test_recuperar_cuenta_post()
    {
        $response = $this->post("/recuperar-cuenta/inventado");
        $response->isInvalid();

        $response = $this->post("/recuperar-cuenta/inventado",
        array(
            "password" => "password",
        ));
        $response->isInvalid();

        $response = $this->post("/recuperar-cuenta/inventado",
            array(
                "password" => "password",
                "password_confirmation" => "diferente",
            ));
        $response->isInvalid();

        $response = $this->post("/recuperar-cuenta",
            array(
                "password" => "password",
                "password_confirmation" => "password",
            ));
        $response->isInvalid();

        $response = $this->post("/recuperar-cuenta",
            array(
                "password" => "password",
                "password_confirmation" => "password",
                "token" => "token"
            ));
        $this->assertEquals(-12, $response->viewData("response")["code"]);

        $userRand = User::factory()->create();
        $token = RecuperarCuentaToken::crearTokenDeRecuperacionCuenta($userRand->id, now()->addDay());

        $response = $this->post("recuperar-cuenta",
            array(
                "password" => "password",
                "password_confirmation" => "password",
                "token" => $token["data"]->token
            ));
        $response->assertStatus(200);
        $this->assertEquals(0, $response->viewData("response")["code"]);
    }

    public function test_recuperar_cuenta_cliente_ok()
    {
        Notification::fake();
        $userRand = User::factory()->create(["email" => "test@test.com"]);

        $response = $this->post("/api/recuperar-cuenta", array(
            "correo" => $userRand->email
        ));

        $response->assertStatus(200);
        Notification::assertSentTo([$userRand], RecuperarCuenta::class);

    }
}
