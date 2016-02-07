<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeaponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_weapons', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('uuid', 32);
            $table->string('contentId', 64);
            $table->string('name', 64);
            $table->string('description', 128)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halo5_weapons');
    }
}
