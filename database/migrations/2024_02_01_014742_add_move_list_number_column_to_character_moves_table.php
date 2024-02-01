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
            $table->integer('move_list_number')->after('frames_on_counter_hit')->nullable();
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
            $table->dropColumn('move_list_number');
        });
    }
};
