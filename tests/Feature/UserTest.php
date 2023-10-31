<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerificarNuevaCuentaUsuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_register_ok()
    {
        $response = User::crearNuevoUsuario("test", "test@test.com", "test");

        $assertableJSON = AssertableJson::fromArray($response);
        $assertableJSON->has("code");
        $assertableJSON->has("data");
        $assertableJSON->where("code", 0);
        $assertableJSON->where("data.name", "test");
        $assertableJSON->where("data.email", "test@test.com");
        $assertableJSON->has("data.id");
    }

    public function test_register_ko_validation_fail()
    {
        $response = $this->post("/api/registrarse",
            array(
                "name" => "test"
            )
        );

        $response->assertInvalid(["email", "password"]);

        $response = $this->post("/api/registrarse",
            array(
                "name" => "test",
                "email" => "test@test.com"
            )
        );

        $response->assertInvalid(["password"]);

        $response = $this->post("/api/registrarse",
            array(
                "name" => "test",
                "email" => "test@test.com",
                "password" => "12345"
            )
        );

        $response->assertInvalid(["password"]);

        $response = $this->post("/api/registrarse",
            array(
                "name" => "test",
                "email" => "test@test.com",
                "password" => "test",
                "password_confirmation" => "diferente"
            )
        );

        $response->assertInvalid("password");
    }

    public function test_register_ok()
    {
        Notification::fake();

        $response = $this->post("/api/registrarse",
            array(
                "name" => "test",
                "email" => "test@test.com",
                "password" => "12345",
                "password_confirmation" => "12345"
            )
        );

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>

                $json->where("name", "test")
                    ->has("id")
                ->where("email", "test@test.com")
                ->has("created_at")
                ->has("updated_at")
                ->has("token_type")
                ->has("access_token")
        );

        Notification::assertSentTo(User::find($response["id"]), VerificarNuevaCuentaUsuario::class);
    }

    public function test_register_ko_falla_el_crear_usuario()
    {
        $this->mock('alias:' . User::class, function (MockInterface $mock) {
            $mock->shouldReceive('crearNuevoUsuario')->once()
                ->andReturn('Some json encoded data');
        })->makePartial();


        $response = $this->post("/api/registrarse",
            array(
                "name" => "test",
                "email" => "test@test.com",
                "password" => "12345",
                "password_confirmation" => "12345"
            )
        );

        dd($response);
    }
}
