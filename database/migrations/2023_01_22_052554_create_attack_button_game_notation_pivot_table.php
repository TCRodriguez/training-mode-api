<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttackButtonGameNotationPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_button_game_notation', function (Blueprint $table) {
            $table->unsignedBigInteger('attack_button_id')->index();
            $table->foreign('attack_button_id')->references('id')->on('attack_buttons')->onDelete('cascade');
            $table->unsignedBigInteger('game_notation_id')->index();
            $table->foreign('game_notation_id')->references('id')->on('game_notations')->onDelete('cascade');
            $table->unsignedBigInteger(('game_id'))->index();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['attack_button_id', 'game_notation_id', 'game_id'], 'attack_button_game_notation_game_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attack_button_game_notation');
    }
}
