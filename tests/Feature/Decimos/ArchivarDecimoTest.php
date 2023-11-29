<?php

namespace Tests\Feature\Decimos;

use App\Models\Decimo;
use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchivarDecimoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sorteo_invent(): void
    {
        $userRand = User::factory()->create(["email_verified_at" => now()]);
        $this->actingAs($userRand);

        $response = $this->get("/api/archivar-decimos/99999",
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );
        $response->assertStatus(404);
    }

    public function test_archivar_sin_sesion()
    {
        $userRand = User::factory()->create(["email_verified_at" => now()]);
        $sorteoRand = Sorteo::factory()->create();

        $response = $this->get("/api/archivar-decimos/".Sorteo::first()->id,
            array(
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            )
        );

        $response->assertStatus(401);
    }

    public function test_archivar_ok()
    {
        $userRand = User::factory()->create(["email_verified_at" => now()]);
        $this->actingAs($userRand);

        $sorteoRand = Sorteo::factory()->create();
        $decimoRand1 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        $decimoRand2 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);

        $this->assertEquals(2, User::find($userRand->id)->decimos->count());
        $response = $this->get("/api/archivar-decimos/".$sorteoRand->id);
        $this->assertEquals(0, User::find($userRand->id)->decimos->count());
        $response->assertStatus(200);
    }
}
