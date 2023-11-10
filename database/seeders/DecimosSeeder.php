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

        $sorteos = Sorteo::all();

        foreach($sorteos as $sorteo){
            Decimo::factory()->count(5)->create([
                "usuario" => $idUserPruebas,
                "sorteo" => $sorteo->id
            ]);
        }
    }
}
