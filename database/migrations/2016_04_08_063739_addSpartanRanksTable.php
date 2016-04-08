<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpartanRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_ranks', function (Blueprint $table)
        {
            $table->integer('level', false, true);
            $table->integer('previousLevel', false, true); // finding table via eager
            $table->uuid('uuid');
            $table->integer('startXp', false, true);

            $table->primary('level');
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halo5_ranks');
    }
}
