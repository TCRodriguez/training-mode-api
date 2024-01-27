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
        Schema::create('device_buttons', function (Blueprint $table) {
            $table->id();
            $table->string('hardware_name');
            $table->string('face_value');
            $table->string('category');
            $table->unsignedBigInteger('device_id')->index();
            $table->timestamps();

            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_buttons');
    }
};
