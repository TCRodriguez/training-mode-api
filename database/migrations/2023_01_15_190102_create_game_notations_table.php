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
            $table->string('text');
            $table->unsignedBigInteger('game_id')->index();
            $table->unsignedBigInteger('directional_input_id')->nullable()->index();
            $table->unsignedBigInteger('attack_button_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');

            $table->foreign('directional_input_id')
                ->references('id')
                ->on('directional_inputs')
                ->onDelete('cascade');

            $table->foreign('attack_button_id')
                ->references('id')
                ->on('attack_buttons')
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
