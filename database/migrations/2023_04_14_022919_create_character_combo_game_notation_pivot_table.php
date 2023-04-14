<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterComboGameNotationPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_combo_game_notation', function (Blueprint $table) {
            $table->unsignedBigInteger('character_combo_id')->index();
            $table->foreign('character_combo_id')
                ->references('id')
                ->on('character_combos')
                ->onDelete('cascade');
            $table->unsignedBigInteger('game_notation_id')->index();
            $table->foreign('game_notation_id')
                ->references('id')
                ->on('game_notations')
                ->onDelete('cascade');
            // $table->primary(['character_combo_id', 'game_notation_id']);
            $table->string('order_in_combo');
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
        Schema::dropIfExists('character_combo_game_notation');
    }
}
