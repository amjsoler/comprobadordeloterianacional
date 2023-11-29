<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\ComprobarTokenFirebase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ComprobarFirebaseToken extends TestCase
{
    use RefreshDatabase;

    public function test_con_sesion_sin_token_almacenado(): void
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

        $request = new Request();
        $request->headers->set("firebasetoken", "token");
        $request->headers->set("Bearer", $token);

        $next = function() { return response(200); };

        $this->assertNull(User::find($response["id"])->firebasetoken);
        (new ComprobarTokenFirebase())->handle($request, $next);
        $this->assertEquals("token", User::find($response["id"])->firebasetoken);
    }

    public function test_con_sesion_con_token_diferente(): void
    {
        $response = $this->post("/api/registrarse",
            array(
                "name" => "test",
                "email" => "test@test.com",
                "password" => "12345",
                "password_confirmation" => "12345",
            )
        );

        $token = $response["access_token"];

        $user = User::find($response["id"]);
        $user->firebasetoken = "tokenInvent";
        $user->save();

        $request = new Request();
        $request->headers->set("firebasetoken", "token");
        $request->headers->set("Bearer", $token);

        $next = function() { return response(200); };

        $this->assertEquals("tokenInvent", User::find($response["id"])->firebasetoken);
        (new ComprobarTokenFirebase())->handle($request, $next);
        $this->assertEquals("token", User::find($response["id"])->firebasetoken);
    }
}
