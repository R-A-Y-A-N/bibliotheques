<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Bibliothecaire',
            'email' => 'biblio@test.com',
            'password' => Hash::make('123456'),
            'role' => 'bibliothecaire',
        ]);

        User::create([
            'name' => 'Adherent',
            'email' => 'user@test.com',
            'password' => Hash::make('123456'),
            'role' => 'adherent',
        ]);
    }
}
