<?php

namespace Tests\Feature\Decimos;

use App\Models\Decimo;
use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VerDecimosTest extends TestCase
{
    use RefreshDatabase;

     public function test_mis_decimos_ko_no_login()
     {
         $response = $this->get("/api/mis-decimos",
         array(
             "Content-Type" => "application/json",
             "Accept" => "application/json"
             )
         );
         $response->assertStatus(401);
     }

    public function test_mis_decimos_ko_cuenta_no_verificada()
    {
        $userRand = User::factory()->create(["email_verified_at" => null]);
        Auth::login($userRand);

        $response = $this->get("/api/mis-decimos",
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );
        $response->assertStatus(460);
    }

    public function test_mis_decimos_ok()
    {
        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        $decimoRand = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        $decimoRand = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        Auth::loginUsingId($userRand->id);

        $response = $this->get("/api/mis-decimos");
        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->missing("data")
        );

        $response = $this->get("/api/mis-decimos");
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has(3)
        );
    }
}
