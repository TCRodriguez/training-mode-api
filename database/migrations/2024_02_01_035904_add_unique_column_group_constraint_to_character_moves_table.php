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
        Schema::table('character_moves', function (Blueprint $table) {
            $table->unique(['name', 'character_id', 'game_id'], 'move_name_character_game_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('character_moves', function (Blueprint $table) {
            $table->dropUnique('move_name_character_game_unique');
        });
    }
};
