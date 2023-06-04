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
        Schema::create('character_move_character_move_condition', function (Blueprint $table) {
            $table->unsignedBigInteger('character_move_id')->index();
            $table->foreign('character_move_id', 'move_id')->references('id')->on('character_moves')->onDelete('cascade');
            $table->unsignedBigInteger('character_move_condition_id')->index('condition_index');
            $table->foreign('character_move_condition_id', 'condition_id')->references('id')->on('character_move_conditions')->onDelete('cascade');
            $table->primary(['character_move_id', 'character_move_condition_id'], 'move_condition');
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
        Schema::dropIfExists('character_move_character_move_condition');
    }
};
