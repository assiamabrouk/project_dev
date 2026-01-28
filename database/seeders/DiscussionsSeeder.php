<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Ressource;

class DiscussionsSeeder extends Seeder
{
    public function run()
    {
        // Récupérer tous les utilisateurs et ressources
        $users = User::all();
        $ressources = Ressource::all();

        // Vérifier s'il y a des utilisateurs et ressources
        if ($users->count() === 0 || $ressources->count() === 0) {
            $this->command->info("Aucun utilisateur ou ressource trouvé, veuillez d'abord remplir les tables users et ressources.");
            return;
        }

        // Générer 50 discussions aléatoires
        for ($i = 0; $i < 200; $i++) {
            DB::table('discussions')->insert([
                'id_ressource' => $ressources->random()->id_ressource,
                'user_id' => $users->random()->id,
                'message' => fake()->sentence(rand(5, 15)), // message aléatoire
                'is_moderated' => fake()->boolean(20), // 20% de chances d'être modéré
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
