<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class WebAuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_guest_login_ko()
    {
        $response = $this->get("/login");
        $response->assertStatus(200);

        $response = $this->post("/login", []);
        $response->assertRedirect("/login");

        $userRand = User::factory()->create();
        Auth::login($userRand);

        $response = $this->get("/login");
        $response->assertRedirect(route("versorteos"));

        $response = $this->post("/login", []);
        $response->assertRedirect(route("versorteos"));
    }
}
