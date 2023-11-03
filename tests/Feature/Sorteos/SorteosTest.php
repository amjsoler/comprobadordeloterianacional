<?php

namespace Tests\Feature\Sorteos;

use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SorteosTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_ko_no_user(){
        //Ver todos los sorteos
        $response = $this->get("/sorteos");
        $response->assertRedirect("/login");

        $response = $this->post("/sorteos/crear");
        $response->assertRedirect("/login");

        $sorteoRand = Sorteo::factory()->create();

        $response = $this->get("/sorteos/$sorteoRand->id/editar");
        $response->assertRedirect("/login");

        $response = $this->put("/sorteos/$sorteoRand->id/modificar");
        $response->assertRedirect("/login");

        $response = $this->get("/sorteos/$sorteoRand->id/eliminar");
        $response->assertRedirect("/login");

        $response = $this->get("/sorteos/$sorteoRand->id/resultados");
        $response->assertRedirect("/login");

        $response = $this->post("/sorteos/$sorteoRand->id/resultados");
        $response->assertRedirect("/login");
    }

    public function test_auth_ko_cuenta_sin_verificar()
    {
        $userRand = User::factory()->create(["email_verified_at" => null]);

        Auth::login($userRand);

        $response = $this->get("/sorteos");
        $response->assertStatus(460);

        $response = $this->post("/sorteos/crear");
        $response->assertStatus(460);

        $sorteoRand = Sorteo::factory()->create();

        $response = $this->get("/sorteos/$sorteoRand->id/editar");
        $response->assertStatus(460);

        $response = $this->put("/sorteos/$sorteoRand->id/modificar");
        $response->assertStatus(460);

        $response = $this->get("/sorteos/$sorteoRand->id/eliminar");
        $response->assertStatus(460);

        $response = $this->get("/sorteos/$sorteoRand->id/resultados");
        $response->assertStatus(460);

        $response = $this->post("/sorteos/$sorteoRand->id/resultados");
        $response->assertStatus(460);
    }

    public function test_auth_ko_no_admin_user(){
        $userRand = User::factory()->create();

        Auth::login($userRand);

        //Ver todos los sorteos
        $response = $this->get("/sorteos");
        $response->assertStatus(403);

        $response = $this->post("/sorteos/crear");
        $response->assertStatus(403);

        $sorteoRand = Sorteo::factory()->create();

        $response = $this->get("/sorteos/$sorteoRand->id/editar");
        $response->assertStatus(403);

        $response = $this->put("/sorteos/$sorteoRand->id/modificar");
        $response->assertStatus(403);

        $response = $this->get("/sorteos/$sorteoRand->id/eliminar");
        $response->assertStatus(403);

        $response = $this->get("/sorteos/$sorteoRand->id/resultados");
        $response->assertStatus(403);

        $response = $this->post("/sorteos/$sorteoRand->id/resultados");
        $response->assertStatus(403);
    }

    public function test_auth_ok(){
        $userRand = User::factory()->create(["email" => env("ADMIN_AUTORIZADO")]);

        Auth::login($userRand);

        //Ver todos los sorteos
        $response = $this->get("/sorteos");
        $response->assertStatus(200);

        $response = $this->post("/sorteos/crear", ["nombre" => "test", "fecha" => now(), "numero_sorteo" => 3]);
        $response->assertRedirect(route("versorteos"));

        $sorteoRand = Sorteo::factory()->create();

        $response = $this->get("/sorteos/$sorteoRand->id/editar");
        $response->assertStatus(200);

        $response = $this->put("/sorteos/$sorteoRand->id/modificar", ["nombre" => "test", "fecha" => now(), "numero_sorteo" => 3]);
        $response->assertRedirect(route("versorteos"));

        $response = $this->get("/sorteos/$sorteoRand->id/resultados");
        $response->assertStatus(200);

        $response = $this->post("/sorteos/$sorteoRand->id/resultados");
        $response->assertRedirect(route("versorteos"));

        $response = $this->get("/sorteos/$sorteoRand->id/eliminar");
        $response->assertRedirect(route("versorteos"));
    }
}
