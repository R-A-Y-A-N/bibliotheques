<?php

namespace Database\Factories;

use App\Models\Penalite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penalite>
 */
class PenaliteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition()
{
    return [
        'emprunt_id' => \App\Models\Emprunt::inRandomOrder()->first()->id,
        'montant' => $this->faker->randomFloat(2, 0, 20),
        'payee' => $this->faker->boolean(),
    ];
}
}
