<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHalo5GametypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_gametypes', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->uuid('contentId');
            $table->string('name', 64)->nullable();
            $table->string('internal_name', 64);
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
        Schema::drop('halo5_gametypes');
    }
}
