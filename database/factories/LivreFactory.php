<?php

namespace Database\Factories;

use App\Models\Livre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Livre>
 */
class LivreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition()
{
    return [
        'titre' => $this->faker->sentence(3),
        'description' => $this->faker->paragraph(),
        'stock' => $this->faker->numberBetween(0, 20),
        'image' => null,
        'nombre_exmp' => $this->faker->numberBetween(0, 20),
        'auteur_id' => \App\Models\Auteur::inRandomOrder()->first()->id,
        'categorie_id' => \App\Models\Categorie::inRandomOrder()->first()->id,
    ];
}
}
