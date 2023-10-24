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
        Sorteo::factory()->count(40)->create();
    }
}
