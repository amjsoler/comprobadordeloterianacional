<?php

namespace Database\Seeders;

use App\Models\Resultado;
use App\Models\Sorteo;
use Illuminate\Database\Seeder;

class ResultadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=1;$i<30;$i++){
            $idSorteo = Sorteo::find($i)->id;

            Resultado::factory()->create([
                "sorteo" => $idSorteo
            ]);
        }
    }
}
