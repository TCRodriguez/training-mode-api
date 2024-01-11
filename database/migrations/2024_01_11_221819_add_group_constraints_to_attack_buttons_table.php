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
        Schema::table('attack_buttons', function (Blueprint $table) {
            $table->unique(['name', 'game_id'], 'game_attack_button_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attack_buttons', function (Blueprint $table) {
            $table->dropUnique('game_attack_button_unique');
        });
    }
};
