<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\ComprobarCuentaVerificada;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ApiAuthTokenGetUser extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_ko_no_token_en_request(): void
    {
        //Creo una request sin meter el token en el header
        $request = Request::create("/invent", "GET");
        $next = function(){ return response("respuesta OK"); };

        $this->assertFalse(Auth::check());
        $responseMiddleware = (new \App\Http\Middleware\ApiAuthTokenGetUser())->handle($request, $next);
        $this->assertFalse(Auth::check());
    }

    public function test_ko_token_no_valido_en_request(): void
    {
        //Creo una request sin meter el token en el header
        $request = Request::create("/invent", "GET");
        $request->headers->set("Authorization", "Bearer 1|invent");

        $next = function(){ return response("respuesta OK"); };

        $this->assertFalse(Auth::check());
        $responseMiddleware = (new \App\Http\Middleware\ApiAuthTokenGetUser())->handle($request, $next);
        $this->assertFalse(Auth::check());
    }

    public function test_ok_token_valido(): void
    {
        $response = $this->post("/api/registrarse",
            array(
                "name" => "test",
                "email" => "test@test.com",
                "password" => "12345",
                "password_confirmation" => "12345"
            )
        );

        $token = $response["access_token"];

        Auth::logout();

        //Creo una request sin meter el token en el header
        $request = Request::create("/invent", "GET");
        $request->headers->set("Authorization", "Bearer " . $token);
        $next = function(){ return response("respuesta OK"); };

        $this->assertFalse(Auth::check());
        $responseMiddleware = (new \App\Http\Middleware\ApiAuthTokenGetUser())->handle($request, $next);
        $this->assertTrue(Auth::check());
    }
}
