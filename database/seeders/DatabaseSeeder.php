<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Utilisateurs
            UserSeeder::class,
            // Catégories et Ressources
            CategorieRessourceSeeder::class,
            RessourceSeeder::class,
            NotificationSeeder::class,

            // Ici, vous pourrez ajouter les autres seeders si nécessaires
            // MaintenanceSeeder::class,
            ReservationSeeder::class,
            DiscussionsSeeder::class,
        ]);
    }
}
