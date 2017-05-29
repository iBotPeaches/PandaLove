<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHalo5MapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_maps', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->uuid('contentId');
            $table->string('name', 64);
            $table->text('description')->nullable();
            $table->json('game_modes');

            $table->primary('uuid');
            $table->index('contentId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('halo5_maps');
    }
}
