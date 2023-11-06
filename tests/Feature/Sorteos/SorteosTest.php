<?php

namespace Tests\Feature\Sorteos;

use App\Events\NuevosResultadosGuardados;
use App\Listeners\ComprobarPremiosDecimosDelResultadoGuardado;
use App\Models\Decimo;
use App\Models\Sorteo;
use App\Models\User;
use App\Notifications\ComprobacionDecimo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
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

        Event::fake(NuevosResultadosGuardados::class);
        $response = $this->post("/sorteos/$sorteoRand->id/resultados");
        Event::assertDispatched(NuevosResultadosGuardados::class);
        $response->assertRedirect(route("versorteos"));

        $response = $this->get("/sorteos/$sorteoRand->id/eliminar");
        $response->assertRedirect(route("versorteos"));
    }

    public function test_listener_comprobar_premios_decimos_del_resultado()
    {
        $sorteoRand1 = Sorteo::factory()->create(["resultados" => "especial;09098&2&4;14870000\r\nprimero;09098;130000\r\nsegundo;89379;25000\r\n4cifras;0181&0471&2965&4992&7010;375\r\n3cifras;031&095&175&206&261&314&337&424&472&615&655&667&766&893&895;75\r\n2cifras;20&99;30\r\naproximacionesprimero;09097&09099;2400\r\naproximacionessegundo;89378&89380;1532\r\ncentenaprimero;09000&09099;75\r\ncentenasegundo;89300&89399;75\r\n3terminacionesprimero;098;75\r\n2terminacionesprimero;98;75\r\n1terminacionprimero;8;15\r\nreintegros;2&5;15"]);
        $sorteoRand2 = Sorteo::factory()->create();
        $userRand = User::factory()->create();

        $decimoRand1 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand1->id]);
        $decimoRand2 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand1->id]);
        $decimoRand3 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand1->id]);
        $decimoRand4 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand2->id]);

        $listener = new ComprobarPremiosDecimosDelResultadoGuardado();
        $event = new NuevosResultadosGuardados($sorteoRand1);

        Notification::fake();

        $this->assertNull($decimoRand1->premio);

        $listener->handle($event);

        $this->assertNotNull(Decimo::find($decimoRand1->id)->premio);
        $this->assertNotNull(Decimo::find($decimoRand2->id)->premio);
        $this->assertNotNull(Decimo::find($decimoRand3->id)->premio);
        $this->assertNull(Decimo::find($decimoRand4->id)->premio);

        Notification::assertCount(3);
        Notification::assertSentTo($userRand, ComprobacionDecimo::class);
    }
}
