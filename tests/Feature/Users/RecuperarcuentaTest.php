<?php

namespace Tests\Feature\Users;

use Tests\TestCase;

class RecuperarcuentaTest extends TestCase
{
    public function test_recuperar_cuenta_ko_validacion_error(): void
    {
        $response = $this->post("/recuperar-cuenta", array());
        $response->assertInvalid(["password"]);

        $response = $this->post("/recuperar-cuenta",
            array(
                "password" => "password"
            )
        );
        $response->assertInvalid(["password"]);

        $response = $this->post("/recuperar-cuenta",
            array(
                "password" => "password",
                "password_confirmation" => "diferente"
            )
        );
        $response->assertInvalid(["password"]);
    }
}
