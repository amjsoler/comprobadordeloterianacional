<?php

namespace Database\Seeders;

use App\Models\Decimo;
use App\Models\Sorteo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DecimosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idUserPruebas = User::where("email", "amjsoler@gmail.com")->first()->id;

        for($i=1;$i<100;$i++){
            $idSorteo = Sorteo::find(rand(1,20))->id;

            Decimo::factory()->create([
                "usuario" => $idUserPruebas,
                "sorteo" => $idSorteo
            ]);
        }
    }
}
