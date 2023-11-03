<?php

namespace Tests\Feature\Decimos;

use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CrearDecimoTest extends TestCase
{
    use RefreshDatabase;
    public function test_crear_decimo_ko_validacion_error(): void
    {
        $userRand = User::factory()->create();

        Auth::loginUsingId($userRand->id);

        $sorteoRand = Sorteo::factory()->create();

        $response = $this->post("/api/mis-decimos",
            array(
                "numero" => "",
                "reintegro" => "",
                "serie" => "invalido",
                "fraccion" => "invalido",
                "sorteo" => ""
            ));

        $response->assertInvalid(["numero", "reintegro", "serie", "fraccion", "sorteo"]);

        $response = $this->post("/api/mis-decimos",
            array(
                "numero" => 55789,
                "reintegro" => "hola",
                "cantidad" => "hola",
                "sorteo" => 99999999
            ));

        $response->assertInvalid(["numero", "reintegro", "cantidad", "sorteo"]);

        $response = $this->post("/api/mis-decimos",
            array(
                "numero" => "99999999999999",
                "reintegro" => "2",
                "serie" => "3",
                "fraccion" => "3",
                "sorteo" => $sorteoRand->id
            ));

        $response->assertInvalid(["numero"]);
    }

    public function test_crear_decimo_ko_no_login()
    {
        $sorteoRand = Sorteo::factory()->create();
        $usuarioRand = User::factory()->create();

        $response = $this->post("/api/mis-decimos",
            array(
                "numero" => "12345",
                "reintegro" => 1,
                "sorteo" => $sorteoRand->id,
                "usuario" => $usuarioRand->id
            ),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );
        $response->assertStatus(401);
    }

    public function test_mis_decimos_ko_cuenta_no_verificada()
    {
        $usuarioRand = User::factory()->create(["email_verified_at" => null]);
        $sorteoRand = Sorteo::factory()->create();
        Auth::login($usuarioRand);

        $response = $this->post("/api/mis-decimos",
            array(
                "numero" => "12345",
                "reintegro" => 1,
                "sorteo" => $sorteoRand->id,
                "usuario" => $usuarioRand->id
            ),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );
        $response->assertStatus(460);
    }

    public function test_crear_decimo_ok()
    {
        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();
        Auth::loginUsingId($userRand->id);

        $response = $this->post("/api/mis-decimos",
            array(
                "numero" => "12345",
                "reintegro" => 1,
                "sorteo" => $sorteoRand->id,
                "usuario" => $userRand->id
            ));
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("numero")
                ->where("numero", "12345")
                ->where("reintegro", 1)
                ->etc()
        );
    }
}
