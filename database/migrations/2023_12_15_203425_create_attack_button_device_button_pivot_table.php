<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttackButtonDeviceButtonPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_button_device_button', function (Blueprint $table) {
            $table->unsignedBigInteger('attack_button_id')->index();
            $table->foreign('attack_button_id')->references('id')->on('attack_buttons')->onDelete('cascade');

            $table->unsignedBigInteger('device_button_id')->index();
            $table->foreign('device_button_id')->references('id')->on('device_buttons')->onDelete('cascade');

            $table->unsignedBigInteger('game_id')->index();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');

            $table->unsignedBigInteger('device_id')->index();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');

            $table->timestamps();

            $table->primary(['attack_button_id', 'device_button_id', 'game_id', 'device_id'], 'attack_button_device_mapping');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attack_button_device_button');
    }
}
