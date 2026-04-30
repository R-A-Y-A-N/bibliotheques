<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Livre;
use App\Models\Emprunt;
use App\Models\Penalite;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */


public function run()
{
    $this->call(UserSeeder::class);
    // Users
    User::factory(20)->create();

    // Auteurs & Categories
    Auteur::factory(10)->create();
    Categorie::factory(5)->create();

    // Livres
    Livre::factory(50)->create();

    // Emprunts
    Emprunt::factory(100)->create();

    // Penalites
    Penalite::factory(50)->create();
}
}
