<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('categorie_ressources', function (Blueprint $table) {
            $table->id('id_categorie');
            $table->string('img')->nullable();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->foreignId('user_id') 
                ->constrained('users') 
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categorie_ressources');
    }
};
