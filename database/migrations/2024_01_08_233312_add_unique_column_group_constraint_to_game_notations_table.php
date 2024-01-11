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
        Schema::table('game_notations', function (Blueprint $table) {
            $table->unique(['notation', 'game_id', 'character_id', 'character_move_id'], 'game_notation_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_notations', function (Blueprint $table) {
            $table->dropUnique('game_notation_unique');
        });
    }
};
