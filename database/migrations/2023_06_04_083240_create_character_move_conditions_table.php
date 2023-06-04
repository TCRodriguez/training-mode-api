<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_move_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('condition')->nullable();
            $table->unsignedBigInteger('character_move_id')->index();
            $table->unsignedBigInteger('game_id')->index();
            $table->timestamps();

            $table->foreign('character_move_id')
                ->references('id')
                ->on('character_moves')
                ->onDelete('cascade');

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_move_conditions');
    }
};
