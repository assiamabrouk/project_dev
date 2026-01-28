<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->id('id_discussion');

            $table->foreignId('id_ressource')
                ->constrained('ressources', 'id_ressource')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('message');

            $table->boolean('is_moderated')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discussions');
    }
};
