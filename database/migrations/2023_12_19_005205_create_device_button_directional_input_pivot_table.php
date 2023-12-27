<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceButtonDirectionalInputPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_button_directional_input', function (Blueprint $table) {
            $table->unsignedBigInteger('device_button_id')->index();
            $table->foreign('device_button_id')->references('id')->on('device_buttons')->onDelete('cascade');

            $table->unsignedBigInteger('directional_input_id')->index();
            $table->foreign('directional_input_id')->references('id')->on('directional_inputs')->onDelete('cascade');

            $table->unsignedBigInteger('device_id')->index();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');

            $table->string('game_shorthand')->nullable();

            $table->string('diagonal_direction');

            $table->timestamps();

            $table->primary(['device_button_id', 'directional_input_id'], 'directional_input_device_mapping');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_button_directional_input');
    }
}
