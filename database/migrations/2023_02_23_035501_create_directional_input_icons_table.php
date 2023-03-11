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
        Schema::create('directional_input_icons', function (Blueprint $table) {
            $table->id();
            $table->string('icon_file_name');
            $table->unsignedBigInteger('directional_input_id')->nullable();
            $table->unsignedBigInteger('attack_button_id')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('directional_input_icons');
    }
};
