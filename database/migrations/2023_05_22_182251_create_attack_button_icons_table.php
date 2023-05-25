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
        Schema::create('attack_button_icons', function (Blueprint $table) {
            $table->id();
            $table->string('icon_file_name');
            $table->unsignedBigInteger('attack_button_id')->index()->nullable();
            $table->unsignedBigInteger('game_id')->index();
            $table->timestamps();

            $table->foreign('attack_button_id')
                ->references('id')
                ->on('directional_inputs')
                ->onDelete('cascade');

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
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
        Schema::dropIfExists('attack_button_icons');
    }
};
