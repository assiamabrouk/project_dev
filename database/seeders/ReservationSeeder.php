<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Ressource;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $ressources = Ressource::where('statut', 'disponible')->get();
        
        if ($users->isEmpty() || $ressources->isEmpty()) {
            return;
        }
        
        for ($i = 0; $i < 10; $i++) {
            $dateStart = Carbon::now()->addDays(rand(1, 30));
            $dateEnd = $dateStart->addDays(rand(1, 7));
            
            Reservation::create([
                'user_id' => $users->random()->id,
                'id_ressource' => $ressources->random()->id_ressource,
                'date_debut' => $dateStart,
                'date_fin' => $dateEnd,
                'justification' => 'Test reservation ' . ($i + 1),
                'statut' => collect(['en_attente', 'approuvee', 'refusee'])->random(),
            ]);
        }
    }
}