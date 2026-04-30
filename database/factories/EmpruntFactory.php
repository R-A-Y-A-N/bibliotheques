<?php

namespace Database\Factories;

use App\Models\Emprunt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Emprunt>
 */
class EmpruntFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    $date_emprunt = $this->faker->dateTimeBetween('-1 month', 'now');

    return [
        'user_id' => \App\Models\User::inRandomOrder()->first()->id,
        'livre_id' => \App\Models\Livre::inRandomOrder()->first()->id,
        'date_emprunt' => $date_emprunt,
        'date_retour_prevue' => (clone $date_emprunt)->modify('+7 days'),
        'date_retour_reelle' => $this->faker->optional()->dateTimeBetween($date_emprunt, 'now'),
    ];
}
}
