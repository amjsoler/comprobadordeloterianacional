<?php

namespace Tests\Feature\Decimos;

use App\Models\Sorteo;
use Tests\TestCase;

class ComprobarDecimoTest extends TestCase
{
    public function test_comprobar_decimo_ko_validacion_error(): void
    {
        $sorteoRand = Sorteo::factory()->create();

        $response = $this->post("/api/comprobar-decimo",
        array(
            "numero" => "",
            "reintegro" => "",
            "serie" => "invalido",
            "fraccion" => "invalido",
            "sorteo" => ""
        ));

        $response->assertInvalid(["numero", "reintegro", "serie", "fraccion", "sorteo"]);

        $response = $this->post("/api/comprobar-decimo",
            array(
                "numero" => 55789,
                "reintegro" => "hola",
                "sorteo" => 99999999
            ));

        $response->assertInvalid(["numero", "reintegro", "sorteo"]);

        $response = $this->post("/api/comprobar-decimo",
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
