<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('historique_ressources', function (Blueprint $table) {
            $table->id('id_historique');

            $table->foreignId('id_ressource')
                ->constrained('ressources', 'id_ressource')
                ->cascadeOnDelete();

            $table->foreignId('id_reservation')
                ->constrained('reservations', 'id_reservation')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('date_debut_utilisation');
            $table->date('date_fin_utilisation')->nullable();

            $table->string('etat'); // active / terminée / annulée

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_ressources');
    }
};
