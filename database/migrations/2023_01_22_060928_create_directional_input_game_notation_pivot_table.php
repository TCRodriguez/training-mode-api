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
        Schema::create('directional_input_game_notation', function (Blueprint $table) {
            $table->unsignedBigInteger('directional_input_id')->index();
            $table->foreign('directional_input_id')->references('id')->on('directional_inputs')->onDelete('cascade');
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
        Schema::dropIfExists('directional_input_game_notation');
    }
};
