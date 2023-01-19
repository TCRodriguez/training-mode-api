<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterComboDirectionalInputPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_combo_directional_input', function (Blueprint $table) {
            $table->unsignedBigInteger('character_combo_id')->index();
            $table->foreign('character_combo_id')
                ->references('id')
                ->on('character_combos')
                ->onDelete('cascade');
            $table->unsignedBigInteger('directional_input_id')->index();
            $table->foreign('directional_input_id')
                ->references('id')
                ->on('directional_inputs')
                ->onDelete('cascade');
            // $table->primary(['character_combo_id', 'directional_input_id']);
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
        Schema::dropIfExists('character_combo_directional_input');
    }
}
