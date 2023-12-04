<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class EliminarCuentaUsuarioTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_eliminar_usuario_sin_sesion(): void
    {
        $response = $this->get('/api/eliminar-cuenta',
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            ));

        $response->assertStatus(401);
    }

    public function test_eliminar_usuario_sin_verificar()
    {
        $userRand = User::factory()->create(
            ["email_verified_at" => null]
        );
        $this->actingAs($userRand);

        $response = $this->get('/api/eliminar-cuenta',
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            ));

        $response->assertStatus(460);
    }

    public function test_eliminar_usuario_ok()
    {
        $userRand = User::factory()->create();
        $this->actingAs($userRand);

        $response = $this->get('/api/eliminar-cuenta',
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            ));

        $response->assertStatus(200);
    }
}
