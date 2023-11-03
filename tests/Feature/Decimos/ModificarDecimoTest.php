<?php

namespace Tests\Feature\Decimos;

use App\Models\Decimo;
use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
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

    public function test_modificar_decimo_ko_no_login()
    {
        $sorteoRand = Sorteo::factory()->create();
        $usuarioRand = User::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $usuarioRand->id, "sorteo" => $sorteoRand->id]);

        $response = $this->put("/api/mis-decimos/$decimoRand->id",
            array(
                "numero" => "12345",
                "reintegro" => 1,
            ),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );
        $response->assertStatus(401);
    }

    public function test_modificar_decimo_ko_cuenta_no_verificada()
    {
        $usuarioRand = User::factory()->create(["email_verified_at" => null]);
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $usuarioRand->id, "sorteo" => $sorteoRand->id]);
        Auth::login($usuarioRand);

        $response = $this->put("/api/mis-decimos/$decimoRand->id",
            array(
                "numero" => "12345",
                "reintegro" => 1,
            ),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );
        $response->assertStatus(460);
    }

    public function test_modificar_decimo_ko_no_autorizacion()
    {
        $sorteoRand = Sorteo::factory()->create();
        $usuarioRand1 = User::factory()->create();
        $usuarioRand2 = User::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $usuarioRand1->id, "sorteo" => $sorteoRand->id]);

        Auth::loginUsingId($usuarioRand2->id);

        $response = $this->put("/api/mis-decimos/$decimoRand->id",
            array(),
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(403);
    }

    public function test_modificar_decimo_ok()
    {
        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        Auth::loginUsingId($userRand->id);

        $response = $this->put("/api/mis-decimos/$decimoRand->id",
            array(
                "numero" => "99999",
                "reintegro" => 9,
                "sorteo" => $sorteoRand->id,
                "usuario" => $userRand->id
            ));
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
        $json->has("numero")
            ->where("numero", "99999")
            ->where("reintegro", 9)
            ->etc()
        );
    }
}
