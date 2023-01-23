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
        Schema::create('game_notations', function (Blueprint $table) {
            $table->id();
            $table->string('notation');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('game_id')->index();
            $table->unsignedBigInteger('character_id')->index()->nullable();
            $table->unsignedBigInteger('character_move_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');

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
        Schema::dropIfExists('game_notations');
    }
};
