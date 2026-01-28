<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('id_reservation');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->text('justification');
            $table->string('statut');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('id_ressource')
                ->constrained('ressources', 'id_ressource');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};