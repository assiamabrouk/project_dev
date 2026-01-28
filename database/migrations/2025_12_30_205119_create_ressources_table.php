<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ressources', function (Blueprint $table) {
            $table->id('id_ressource');
            $table->string('nom');
            $table->string('img')->nullable();
            $table->text('description')->nullable();
            $table->string('cpu');
            $table->string('ram');
            $table->string('capacite_stockage');
            $table->string('bande_passante');
            $table->string('os');
            $table->string('localisation');
            $table->string('statut');
            $table->timestamps(); 
            $table->foreignId('id_categorie')
                ->constrained('categorie_ressources', 'id_categorie');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ressources');
    }
};
