<?php

namespace Database\Factories;

use App\Models\Auteur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Auteur>
 */
class AuteurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition()
{
    return [
        'nom' => $this->faker->word(),
    ];
}
}
