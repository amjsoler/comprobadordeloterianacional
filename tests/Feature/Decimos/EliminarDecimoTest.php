<?php

namespace Tests\Feature\Decimos;

use App\Models\Decimo;
use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EliminarDecimoTest extends TestCase
{
    public function test_eliminar_decimo_ko_no_auth()
    {
        $sorteoRand = Sorteo::factory()->create();
        $usuarioRand = User::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $usuarioRand->id, "sorteo" => $sorteoRand->id]);

        $response = $this->delete("/api/mis-decimos/$decimoRand->id",
            array(),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(401);
    }

    public function test_eliminar_decimo_ko_cuenta_no_verificada()
    {
        $sorteoRand = Sorteo::factory()->create();
        $usuarioRand = User::factory()->create(["email_verified_at" => null]);
        $decimoRand = Decimo::factory()->create(["usuario" => $usuarioRand->id, "sorteo" => $sorteoRand->id]);

        Auth::loginUsingId($usuarioRand->id);

        $response = $this->delete("/api/mis-decimos/$decimoRand->id",
            array(),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(460);
    }

    public function test_eliminar_decimo_ko_sin_autorizacion()
    {
        $sorteoRand = Sorteo::factory()->create();
        $usuarioRand1 = User::factory()->create();
        $usuarioRand2 = User::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $usuarioRand1->id, "sorteo" => $sorteoRand->id]);

        Auth::loginUsingId($usuarioRand2->id);

        $response = $this->delete("/api/mis-decimos/$decimoRand->id",
            array(),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(403);
    }

    public function test_eliminar_decimo_ok()
    {
        $sorteoRand = Sorteo::factory()->create();
        $usuarioRand = User::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $usuarioRand->id, "sorteo" => $sorteoRand->id]);

        Auth::loginUsingId($usuarioRand->id);

        $response = $this->delete("/api/mis-decimos/$decimoRand->id",
            array(),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(200);
    }
}
