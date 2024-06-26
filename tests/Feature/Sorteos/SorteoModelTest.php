<?php

namespace Tests\Feature\Sorteos;

use App\Models\Sorteo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SorteoModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_dame_resultados_dado_el_sorteo(): void
    {
        //Sorteo que no existe
        $assertableJSON = AssertableJson::fromArray(
            Sorteo::dameResultadosDadoElSorteo(999999999999999999));

        $assertableJSON->where("code", -2);

        //Sorteo existente sin resultados
        $sorteoFake = Sorteo::factory()->create();

        $assertableJSON = AssertableJson::fromArray(
            Sorteo::dameResultadosDadoElSorteo($sorteoFake->id));

        $assertableJSON->where("code", -2);

        //Ahora guardo resultados
        $sorteoFake->resultados = "resultados";
        $sorteoFake->save();

        $assertableJSON = AssertableJson::fromArray(
            Sorteo::dameResultadosDadoElSorteo($sorteoFake->id));

        $assertableJSON->where("code", 0);
    }

    public function test_dame_fechas_sorteo_in_array_de_fechas()
    {
        $sorteo1 = Sorteo::factory()->create();
        $sorteo2 = Sorteo::factory()->create();

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::dameFechasSorteoInArrayDeFechas([])
        );
        $responseAssertJson->where("code", 0);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::dameFechasSorteoInArrayDeFechas([$sorteo1->fecha])
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->has("data", 1);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::dameFechasSorteoInArrayDeFechas([$sorteo1->fecha, $sorteo2->fecha])
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->has("data", 2);
    }

    public function test_cruza_fechas_sorteos_existentes_sin_resultados_en_bd_con_array_fechas()
    {
        $sorteo1 = Sorteo::factory()->create();
        $sorteo2 = Sorteo::factory()->create();

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas([])
        );
        $responseAssertJson->where("code", 0);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas([$sorteo1->fecha])
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->has("data", 1);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas([$sorteo1->fecha, $sorteo2->fecha])
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->has("data", 2);


        $sorteo1->resultados = "resultados";
        $sorteo1->save();

        $sorteo2->resultados = "resultados";
        $sorteo2->save();

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas([$sorteo1->fecha])
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->has("data", 0);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas([$sorteo1->fecha, $sorteo2->fecha])
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->has("data", 0);
    }

    public function test_sorteos_disponibles()
    {
        $sorteo1 = Sorteo::factory()->create([
            "fecha" => now()->subDay()
        ]);

        $sorteo2 = Sorteo::factory()->create([
            "fecha" => now()->addDay()
        ]);

        $sorteo3 = Sorteo::factory()->create([
            "fecha" => now()->addDay()
        ]);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::sorteosDisponibles()
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->count("data", 2);

        $sorteo4 = Sorteo::factory()->create([
            "fecha" => now()->addDay()
        ]);
        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::sorteosDisponibles()
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->count("data", 3);

        $sorteo5 = Sorteo::factory()->create([
            "fecha" => now()->addDay(),
            "resultados" => "algo"
        ]);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::sorteosDisponibles()
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->count("data", 3);
    }

    public function test_dame_ultimos_sorteos_con_resultados()
    {
        $sorteo1 = Sorteo::factory()->create([
            "fecha" => now()->subDay(),
            "resultados" => "algo"
        ]);

        $sorteo2 = Sorteo::factory()->create([
            "fecha" => now()->subDay(5),
            "resultados" => "algo2"
        ]);

        $sorteo3 = Sorteo::factory()->create([
            "fecha" => now()->subDay(10),
            "resultados" => "algo3"
        ]);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::dameUltimosSorteosConResultado(10)
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->count("data", 3);

        $sorteo4 = Sorteo::factory()->create([
            "fecha" => now()->subDay(3),
            "resultados" => "algo4"
        ]);

        $sorteo5 = Sorteo::factory()->create([
            "fecha" => now()->subDay(2),
        ]);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::dameUltimosSorteosConResultado(10)
        );
        $responseAssertJson->where("code", 0);
        $responseAssertJson->count("data", 4);
        $responseAssertJson->where("data.1.resultados", "algo4");
    }

    public function test_id_sorteo_dada_fecha()
    {
        $this->refreshDatabase();

        $sorteo1 = Sorteo::factory()->create([
            "fecha" => "2023-11-10",
            "resultados" => "algo"
        ]);

        $sorteo2 = Sorteo::factory()->create([
            "fecha" => "2023-11-9",
            "resultados" => "algo2"
        ]);

        $sorteo3 = Sorteo::factory()->create([
            "fecha" => "2023-09-03",
            "resultados" => "algo3"
        ]);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::dameIdSorteoDadaFecha("2023-01-01")
        )->where("code", -2);

        $responseAssertJson = AssertableJson::fromArray(
            Sorteo::dameIdSorteoDadaFecha("2023-11-09")
        )->where("code", 0)
            ->where("data.fecha", "2023-11-09");
    }
}
