<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('decision_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('decision');
            $table->text('commentaire')->nullable();
            $table->timestamp('date_decision');
            $table->foreignId('id_reservation')
                ->constrained('reservations', 'id_reservation')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('decision_reservations');
    }
};

