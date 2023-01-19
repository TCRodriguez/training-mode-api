<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterComboCharacterMovePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_combo_character_move', function (Blueprint $table) {
            $table->unsignedBigInteger('character_combo_id')->index();
            $table->foreign('character_combo_id')->references('id')->on('character_combos')->onDelete('cascade');
            $table->unsignedBigInteger('character_move_id')->index();
            $table->foreign('character_move_id')->references('id')->on('character_moves')->onDelete('cascade');
            // $table->primary(['character_combo_id', 'character_move_id']);
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
        Schema::dropIfExists('character_combo_character_move');
    }
}
