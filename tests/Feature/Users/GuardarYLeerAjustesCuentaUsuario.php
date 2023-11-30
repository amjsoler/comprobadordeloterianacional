<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GuardarYLeerAjustesCuentaUsuario extends TestCase
{
    use RefreshDatabase;

    public function test_guardar_y_leer_ajustes_cuenta_usuario()
    {
        $response = $this->get('/api/ajustes-cuenta',
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(401);

        $response = $this->post("/api/ajustes-cuenta",
            array(
                "alertasporcorreo" => 0,
                "alertaspornotificacion" => 1
            ),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );
        $response->assertStatus(401);

        $userRand = User::factory()->create();
        $this->actingAs($userRand);

        $response = $this->get("/api/ajustes-cuenta");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where("alertasporcorreo", 1)
            ->where("alertaspornotificacion", 0)
        );

        $response = $this->post("/api/ajustes-cuenta",
            array(
                "alertasporcorreo" => 0,
                "alertaspornotificacion" => 1
            ));
        $response->assertStatus(200);

        $response = $this->get("/api/ajustes-cuenta");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where("alertasporcorreo", 0)
            ->where("alertaspornotificacion", 1)
        );
    }
}
