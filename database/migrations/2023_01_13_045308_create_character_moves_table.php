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
        Schema::create('character_moves', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('character_id')->index();
            $table->string('zone')->nullable();
            $table->integer('damage')->nullable();
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->integer('startup_frames')->nullable();
            $table->integer('active_frames')->nullable();
            $table->integer('recovery_frames')->nullable();
            $table->integer('frames_on_hit')->nullable();
            $table->integer('frames_on_block')->nullable();
            $table->integer('frames_on_counter_hit')->nullable();
            $table->timestamps();

            $table->foreign('character_id')
                ->references('id')
                ->on('characters')
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
        Schema::dropIfExists('character_moves');
    }
};
