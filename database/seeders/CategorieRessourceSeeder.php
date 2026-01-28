<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategorieRessource;

class CategorieRessourceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Serveurs physiques',
            'Machines virtuelles',
            'Stockage',
            'Équipements réseau',
            'Réseau virtuel',
            'Sécurité'
        ];

        $i=0;
        foreach ($categories as $categorie) {
            CategorieRessource::create([
                'nom' => $categorie,
                'description' => $categorie,
                'img' => 'categorie_images/cat.png',
                'user_id' => rand(2, 3),
            ]);
        }
    }
}
