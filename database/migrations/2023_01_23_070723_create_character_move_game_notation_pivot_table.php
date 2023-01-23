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
        Schema::create('character_move_game_notation', function (Blueprint $table) {
            $table->unsignedBigInteger('character_move_id')->index();
            $table->foreign('character_move_id')->references('id')->on('character_moves')->onDelete('cascade');
            $table->unsignedBigInteger('game_notation_id')->index();
            $table->foreign('game_notation_id')->references('id')->on('game_notations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_move_game_notation');
    }
};
