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
        Schema::create('character_move_hit_zone', function (Blueprint $table) {
            $table->unsignedBigInteger('character_move_id')->index();
            $table->foreign('character_move_id')
                ->references('id')
                ->on('character_moves')
                ->onDelete('cascade');
            $table->unsignedBigInteger('hit_zone_id')->index();
            $table->foreign('hit_zone_id')->references('id')->on('hit_zones')->onDelete('cascade');
            $table->string('order_in_zone_list');
            $table->primary(['character_move_id', 'hit_zone_id', 'order_in_zone_list'], 'attack_zone_in_move');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_move_hit_zone');
    }
};
