<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterMoveDirectionalInputPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_move_directional_input', function (Blueprint $table) {
            $table->unsignedBigInteger('character_move_id')->index();
            $table->foreign('character_move_id')->references('id')->on('character_moves')->onDelete('cascade');
            $table->unsignedBigInteger('directional_input_id')->index();
            $table->foreign('directional_input_id')->references('id')->on('directional_inputs')->onDelete('cascade');
            // $table->primary(['character_move_id', 'directional_input_id']);
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
        Schema::dropIfExists('character_move_directional_input');
    }
}
