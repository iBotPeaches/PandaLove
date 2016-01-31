<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeasonMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_seasons', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('contentId');
            $table->string('name', 32);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('isActive')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halo5_seasons');
    }
}
