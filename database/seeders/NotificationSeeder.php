<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationSeeder extends Seeder{
    public function run(): void{
        $now = Carbon::now();
        DB::table('notifications')->insert([
            ['user_id' => 1, 'message' => 'Bienvenue dans DataCenterPro!', 'lu' => false, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'message' => 'Votre première réservation a été confirmée.', 'lu' => false, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 2, 'message' => 'Bienvenue dans DataCenterPro!', 'lu' => false, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 2, 'message' => 'Votre première réservation a été confirmée.', 'lu' => false, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 3, 'message' => 'Bienvenue dans DataCenterPro!', 'lu' => false, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 3, 'message' => 'Votre première réservation a été confirmée.', 'lu' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
