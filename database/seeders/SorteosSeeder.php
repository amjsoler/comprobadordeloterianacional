<?php

namespace Database\Seeders;

use App\Models\Sorteo;
use Illuminate\Database\Seeder;

class SorteosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resultadosAleatorios = [
            "primero;09098;130000\r\nsegundo;89378;25000\r\nreintegros;2&5;15",
            "especial;09098&2&4;14870000\r\nprimero;09098;130000\r\nsegundo;89378;25000\r\n4cifras;0181&0471&2965&4992&7010;375\r\n3cifras;031&095&175&206&261&314&337&424&472&615&655&667&766&893&895;75\r\n2cifras;20&99;30\r\naproximacionesprimero;09097&09099;2400\r\naproximacionessegundo;89378&89380;1532\r\ncentenaprimero;09000&09099;75\r\ncentenasegundo;89300&89399;75\r\n3terminacionesprimero;098;75\r\n2terminacionesprimero;98;75\r\n1terminacionprimero;8;15\r\nreintegros;2&5;15"
        ];

        $nombreSorteos = ["Sorteo de Jueves", "Sorteo de Sábado"];

        //Creo sorteos caducados sin resultado
        for($i=1;$i<=20;$i++){
            Sorteo::factory()->create([
                "nombre" => $nombreSorteos[rand(0,1)],
                "numero_sorteo" => $i,
                "fecha" => now()->subDay($i)
            ]);
        }

        //Creo sorteos caducados con resultado
        for($i=1;$i<=20;$i++){
            Sorteo::factory()->create([
                "nombre" => $nombreSorteos[rand(0,1)],
                "numero_sorteo" => $i+20,
                "fecha" => now()->subDay($i),
                "resultados" => $resultadosAleatorios[rand(0,1)]
            ]);
        }

        //Creo sorteos futuros
        for($i=1;$i<=20;$i++){
            Sorteo::factory()->create([
                "nombre" => $nombreSorteos[rand(0,1)],
                "numero_sorteo" => $i+40,
                "fecha" => now()->addDay($i)
            ]);
        }
    }
}
