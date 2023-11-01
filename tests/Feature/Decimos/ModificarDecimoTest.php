<?php

namespace Tests\Feature\Decimos;

use App\Models\Decimo;
use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ModificarDecimoTest extends TestCase
{
    public function test_modificar_decimo_ko_validacion_error(): void
    {
        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);

        Auth::loginUsingId($userRand->id);

        $response = $this->put("/api/mis-decimos/".$decimoRand->id,
            array(
                "numero" => "",
                "reintegro" => "",
                "serie" => "invalido",
                "fraccion" => "invalido",
                "cantidad" => "invalido",
                "sorteo" => ""
            ));

        $response->assertInvalid(["numero", "reintegro", "serie", "fraccion", "cantidad", "sorteo"]);

        $response = $this->put("/api/mis-decimos/".$decimoRand->id,
            array(
                "numero" => 99999,
                "reintegro" => "invalido",
                "sorteo" => 9999999999999999
            ));

        $response->assertInvalid(["numero", "reintegro", "sorteo"]);

        $response = $this->put("/api/mis-decimos/".$decimoRand->id,
            array(
                "numero" => "999999999999999999",
                "reintegro" => 1,
                "sorteo" => $sorteoRand->id
            ));

        $response->assertInvalid(["numero"]);
    }
}
