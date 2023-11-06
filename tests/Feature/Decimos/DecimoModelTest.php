<?php

namespace Tests\Feature\Decimos;

use App\Models\Decimo;
use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DecimoModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_dame_mis_decimos()
    {
        $userRand = User::factory()->create();
        $assertableJsonResponse = AssertableJson::fromArray(Decimo::dameMisDecimos($userRand));
        $assertableJsonResponse->has("code");
        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->has("data");
        $assertableJsonResponse->count("data", 0);

        //Ahora le añado un décimo al usuario
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand = Decimo::factory()->create(["sorteo" => $sorteoRand->id, "usuario" => $userRand->id]);


        $assertableJsonResponse = AssertableJson::fromArray(Decimo::dameMisDecimos(User::find($userRand->id)));
        $assertableJsonResponse->has("code");
        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->has("data");
        $assertableJsonResponse->count("data", 1);

        Decimo::factory()->create(["sorteo" => $sorteoRand->id, "usuario" => $userRand->id]);
        $assertableJsonResponse = AssertableJson::fromArray(Decimo::dameMisDecimos(User::find($userRand->id)));
        $assertableJsonResponse->has("code");
        $assertableJsonResponse->where("code", 0);
        $assertableJsonResponse->has("data");
        $assertableJsonResponse->count("data", 2);
    }

    public function test_crear_decimo()
    {
        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();

        $assertableJsonResponse = AssertableJson::fromArray(Decimo::crearDecimo(
            $userRand->id,
            "13245",
            "2",
            "1",
            "1",
            "3",
            $sorteoRand->id));
        $assertableJsonResponse->has("code");
        $assertableJsonResponse->where("code", 0);

        $assertableJsonResponse = AssertableJson::fromArray(Decimo::crearDecimo(
            $userRand->id,
            "",
            3,
            null,
            null,
            null,
            $sorteoRand->id));
        $assertableJsonResponse->has("code");
        $assertableJsonResponse->where("code", 0);
    }

    public function test_modificar_decimo()
    {
        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        $decimo = Decimo::find($decimoRand->id);

        $this->assertEquals($decimoRand->nombre, $decimo->nombre);
        $this->assertEquals($decimoRand->numero, $decimo->numero);
        $this->assertEquals($decimoRand->cantidad, $decimo->cantidad);

        $decimo->numero = "99999";
        $decimo->cantidad = 99;
        $decimo->save();

        $this->assertEquals("99999", Decimo::find($decimoRand->id)->numero);
        $this->assertEquals(99, Decimo::find($decimoRand->id)->cantidad);
    }

    public function test_eliminar_decimo()
    {
        $this->refreshDatabase();

        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);

        $this->assertDatabaseCount("decimos", 1);

        $this->assertEquals(0, Decimo::onlyTrashed()->count());
        $assertableJsonResponse = AssertableJson::fromArray(Decimo::eliminarDecimo(Decimo::find($decimoRand->id)));

        $assertableJsonResponse->where("code", 0);
        $this->assertEquals(1, Decimo::onlyTrashed()->count());
        $this->assertDatabaseCount("decimos", 1);
    }

    public function test_dame_decimos_sin_comprobar_dado_el_sorteo_with_user()
    {
        $this->refreshDatabase();

        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand1 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        $decimoRand2 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        $decimoRand3 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);

        $decimos = Decimo::dameDecimosSinComprobarDadoElSorteoWithUser($sorteoRand)["data"];
        $this->assertEquals(3, count($decimos));

        $decimoRand1->premio = 15;
        $decimoRand1->save();

        $decimos = Decimo::dameDecimosSinComprobarDadoElSorteoWithUser($sorteoRand)["data"];
        $this->assertEquals(2, count($decimos));

        $decimoRand2->premio = 15;
        $decimoRand2->save();
        $decimoRand3->premio = 15;
        $decimoRand3->save();

        $decimos = Decimo::dameDecimosSinComprobarDadoElSorteoWithUser($sorteoRand)["data"];
        $this->assertEquals(0, count($decimos));
    }

    public function test_asignar_premio()
    {
        $this->refreshDatabase();

        $userRand = User::factory()->create();
        $sorteoRand = Sorteo::factory()->create();
        $decimoRand1 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        $decimoRand2 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);
        $decimoRand3 = Decimo::factory()->create(["usuario" => $userRand->id, "sorteo" => $sorteoRand->id]);

        $this->assertNull($decimoRand1->premio);

        $decimoRand1->premio = "15";
        $decimoRand1->save();
        $this->assertEquals(15, $decimoRand1->premio);

        $decimoRand2->premio = 100;
        $decimoRand2->save();
        $this->assertEquals("100", $decimoRand2->premio);

        $decimoRand3->premio = "1500";
        $decimoRand3->save();
        $this->assertEquals("1500", $decimoRand3->premio);
    }
}
