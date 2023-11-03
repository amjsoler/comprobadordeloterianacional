<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_ko_validation_fail()
    {
        $response = $this->post("/api/registrarse",
            array(
            )
        );

        $response->assertInvalid(["name", "email", "password"]);

        $response = $this->post("/api/registrarse",
            array(
                "name" => Str::random(101),
                "email" => "invalid buen invent",
                "password" => "password"
            )
        );

        $response->assertInvalid(["name", "email", "password"]);

        $userRand = User::factory()->create();

        $response = $this->post("/api/registrarse",
            array(
                "name" => "test",
                "email" => $userRand->email,
                "password" => "12345",
                "password_confirmation" => "diferente"
            )
        );

        $response->assertInvalid(["email", "password"]);
    }

    public function test_register_ok()
    {
        $response = $this->post("/api/registrarse",
        array(
            "name" => "test",
            "email" => "test@test.com",
            "password" => "12345",
            "password_confirmation" => "12345"
        ));

        $response->assertStatus(200);
    }
}
