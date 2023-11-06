<?php

namespace Tests\Feature;

use App\Helpers\Helpers;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_comprobar_premio()
    {
        $resultados = "especial;09098&2&4;14870000\r\nprimero;09098;130000\r\nsegundo;89379;25000\r\n4cifras;0181&0471&2965&4992&7010;375\r\n3cifras;031&095&175&206&261&314&337&424&472&615&655&667&766&893&895;75\r\n2cifras;20&99;30\r\naproximacionesprimero;09097&09099;2400\r\naproximacionessegundo;89378&89380;1532\r\ncentenaprimero;09000&09099;75\r\ncentenasegundo;89300&89399;75\r\n3terminacionesprimero;098;75\r\n2terminacionesprimero;98;75\r\n1terminacionprimero;8;15\r\nreintegros;2&5;15";

        //Especial
        $response = Helpers::comprobarDecimo($resultados, "09098", "2", "2", 4);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "14870000");

        //Primero
        $response = Helpers::comprobarDecimo($resultados, "09098", "5", "5", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "130000");

        //Segundo
        $response = Helpers::comprobarDecimo($resultados, "89379", "", "", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "25000");

        //4 Cifras
        $response = Helpers::comprobarDecimo($resultados, "0181", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "375");

        $response = Helpers::comprobarDecimo($resultados, "01813", "7", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", 0);

        $response = Helpers::comprobarDecimo($resultados, "30181", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "375");

        $response = Helpers::comprobarDecimo($resultados, "10471", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "375");

        $response = Helpers::comprobarDecimo($resultados, "62965", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "375");

        //3 cifras
        $response = Helpers::comprobarDecimo($resultados, "87337", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        //2 cifras
        $response = Helpers::comprobarDecimo($resultados, "87699", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "30");

        //Aproximaciones 1º
        $response = Helpers::comprobarDecimo($resultados, "09097", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "2400");

        $response = Helpers::comprobarDecimo($resultados, "09099", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "2400");

        //Aproximaciones 2º
        $response = Helpers::comprobarDecimo($resultados, "89378", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "1532");

        $response = Helpers::comprobarDecimo($resultados, "89380", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "1532");

        //Centena primero
        $response = Helpers::comprobarDecimo($resultados, "09000", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        $response = Helpers::comprobarDecimo($resultados, "09099", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "2400");

        //Centena segundo
        $response = Helpers::comprobarDecimo($resultados, "89300", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        $response = Helpers::comprobarDecimo($resultados, "89399", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        //3 terminaciones primero
        $response = Helpers::comprobarDecimo($resultados, "34098", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        $response = Helpers::comprobarDecimo($resultados, "098", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        $response = Helpers::comprobarDecimo($resultados, "00098", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        //2 terminaciones primero
        $response = Helpers::comprobarDecimo($resultados, "34198", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        $response = Helpers::comprobarDecimo($resultados, "98", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        $response = Helpers::comprobarDecimo($resultados, "00298", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "75");

        //1 terminacion primero
        $response = Helpers::comprobarDecimo($resultados, "34188", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "15");

        $response = Helpers::comprobarDecimo($resultados, "8", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "15");

        $response = Helpers::comprobarDecimo($resultados, "00228", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "15");

        //Reintegros
        $response = Helpers::comprobarDecimo($resultados, "99992", "2", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "15");

        $response = Helpers::comprobarDecimo($resultados, "99995", "5", "4", 1);
        AssertableJson::fromArray($response)->has("data")->where("data.premioTotal", "15");
    }
}
