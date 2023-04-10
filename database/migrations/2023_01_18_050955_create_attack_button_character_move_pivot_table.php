<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttackButtonCharacterMovePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_button_character_move', function (Blueprint $table) {
            $table->unsignedBigInteger('attack_button_id')->index();
            $table->foreign('attack_button_id')->references('id')->on('attack_buttons')->onDelete('cascade');
            $table->unsignedBigInteger('character_move_id')->index();
            $table->foreign('character_move_id')->references('id')->on('character_moves')->onDelete('cascade');
            $table->string('order_in_move');
            $table->primary(['attack_button_id', 'character_move_id', 'order_in_move'], 'attack_in_move');
            // $table->unique(['attack_button_id', 'character_move_id', 'order_in_move'], "test");
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
        Schema::dropIfExists('attack_button_character_move');
    }
}
