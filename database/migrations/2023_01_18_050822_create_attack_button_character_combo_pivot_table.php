<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttackButtonCharacterComboPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_button_character_combo', function (Blueprint $table) {
            $table->unsignedBigInteger('attack_button_id')->index();
            $table->foreign('attack_button_id')
                ->references('id')
                ->on('attack_buttons')
                ->onDelete('cascade');
            $table->unsignedBigInteger('character_combo_id')->index();
            $table->foreign('character_combo_id')
                ->references('id')
                ->on('character_combos')
                ->onDelete('cascade');
            // $table->primary(['attack_button_id', 'character_combo_id']);
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
        Schema::dropIfExists('attack_button_character_combo');
    }
}
