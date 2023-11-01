<?php

namespace Tests\Feature\Decimos;

use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CrearDecimoTest extends TestCase
{
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
}
