<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'nom' => 'Admin',
                'prenom' => 'Sikan',
                'email' => 'admin@test.com',
                'telephone' => '0612345678',
                'img' => 'user1.png',
                'password' => Hash::make('password123'),
                'user_type' => 'ingénieur',
                'role' => 'admin',
                'statut' => 'actif',
                'created_at' => $now,
                'updated_at' => $now,
                'email_verified_at' => $now,
            ],
            [
                'nom' => 'Benali',
                'prenom' => 'Marouan',
                'email' => 'responsable@test.com',
                'telephone' => '0698765432',
                'img' => 'user2.png',
                'password' => Hash::make('password123'),
                'user_type' => 'doctorant',
                'role' => 'responsable',
                'statut' => 'actif',
                'created_at' => $now,
                'updated_at' => $now,
                'email_verified_at' => $now,
            ],
            [
                'nom' => 'Ahmed',
                'prenom' => 'Jalal',
                'email' => 'responsable2@test.com',
                'telephone' => '0698765432',
                'img' => 'user2.png',
                'password' => Hash::make('password123'),
                'user_type' => 'doctorant',
                'role' => 'responsable',
                'statut' => 'actif',
                'created_at' => $now,
                'updated_at' => $now,
                'email_verified_at' => $now,
            ],
            [
                'nom' => 'Utilisateur',
                'prenom' => 'Standard',
                'email' => 'user@test.com',
                'telephone' => '0678901234',
                'img' => 'user3.png',
                'password' => Hash::make('password123'),
                'user_type' => 'enseignant',
                'role' => 'user',
                'statut' => 'actif',
                'created_at' => $now,
                'updated_at' => $now,
                'email_verified_at' => $now,
            ],
        ]);
    }
}
