<?php

namespace Tests\Feature\Users;

use App\Models\AccountVerifyToken;
use App\Models\User;
use App\Notifications\VerificarNuevaCuentaUsuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class VerificarCuentaTest extends TestCase
{
    public function test_verificar_cuenta_ko_sin_token(): void
    {
        $response = $this->get('/verificar-cuenta');
        $response->assertStatus(404);

        $response = $this->get('/verificar-cuenta/');
        $response->assertStatus(404);

        $response = $this->get('/verificar-cuenta/invent');
        $response->assertStatus(200);
        $this->assertEquals(-12, $response->viewData("response")["code"]);
    }

    public function test_verificar_cuenta_ok()
    {
        $userRand = User::factory()->create(["email_verified_at" => null]);
        $accoutVerify = AccountVerifyToken::crearTokenDeVerificacion($userRand->id, now()->addDay());

        $response = $this->get('/verificar-cuenta/'.$accoutVerify["data"]->token);
        $response->assertStatus(200);
        $this->assertEquals(0, $response->viewData("response")["code"]);
    }

    public function test_api_verificar_cuenta_ko_no_authenticated()
    {
        $response = $this->get("/api/verificar-cuenta",
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(401);
    }

    public function test_api_verificar_cuenta_ok()
    {
        Notification::fake();

        $userRand = User::factory()->create();
        Auth::loginUsingId($userRand->id);

        $response = $this->get("/api/verificar-cuenta",
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(200);
        Notification::assertSentTo([$userRand], VerificarNuevaCuentaUsuario::class);
    }
}
