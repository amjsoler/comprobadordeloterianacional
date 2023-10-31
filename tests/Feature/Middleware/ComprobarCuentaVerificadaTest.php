<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\ComprobarCuentaVerificada;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class ComprobarCuentaVerificadaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_cuenta_no_verificada(): void
    {
        $user = User::factory()->make([
            "email_verified_at" => null
        ]);

        $middleware = new ComprobarCuentaVerificada();
        $request = Request::create("/rutainvent", "GET");

        $response = $middleware->handle($request, function(){});

        $this->assertEquals(460, $response->getStatusCode());

        $this->actingAs($user);
        $response = $middleware->handle($request, function(){});

        $this->assertEquals(460, $response->getStatusCode());
    }

    public function test_cuenta_verificada(): void
    {
        $user = User::factory()->make();

        $middleware = new ComprobarCuentaVerificada();
        $request = Request::create("/rutainvent", "GET");
        $next = function(){ return response("redirect OK"); };

        $this->actingAs($user);

        $response = $middleware->handle($request, $next);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
