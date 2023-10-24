<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Decimo>
 */
class DecimoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => $this->faker->numberBetween(0, 99999),
            'reintegro' => $this->faker->numberBetween(1, 9),
            'serie' => $this->faker->numberBetween(0, 100),
            'fraccion' => $this->faker->numberBetween(1, 10),
            'cantidad' => $this->faker->numberBetween(1, 10),
        ];
    }
}
