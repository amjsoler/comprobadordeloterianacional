<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
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
}
