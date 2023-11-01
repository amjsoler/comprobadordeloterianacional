<?php

namespace Tests\Feature\Sorteos;

use App\Models\Sorteo;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SorteoModelTest extends TestCase
{
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
}