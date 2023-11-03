<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_ko_validacion_error(): void
    {
        $response = $this->post("/api/iniciar-sesion",
        array(

        ));

        $response->assertInvalid(["email", "password"]);

        $response = $this->post("/api/iniciar-sesion",
            array(
                "email" => "",
                "password" => ""
            ));

        $response->assertInvalid(["email", "password"]);

        $response = $this->post("/api/iniciar-sesion",
            array(
                "email" => "invalido",
                "password" => "password"
            ));

        $response->assertInvalid(["email"]);

        $response = $this->post("/api/iniciar-sesion",
            array(
                "email" => "test@test.com",
                "password" => "password",
            ));

        $response->assertInvalid(["email"]);
    }

    public function test_login_()
    {
        $userRand = User::factory()->create(["email" => "test@test.com", "password" => "password"]);

        $response = $this->post("/api/iniciar-sesion",
        array(
            "email" => "test@test.com",
            "password" => "invent"
        ));
        $response->assertStatus(401);

        $response = $this->post("/api/iniciar-sesion",
            array(
                "email" => "test@test.com",
                "password" => "password"
            ));
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("access_token")
            ->has("token_type")
        );
    }
}
