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
        for($i=1;$i<=102;$i++){
            Sorteo::factory()->create([
                "numero_sorteo" => $i
            ]);
        }
    }
}
