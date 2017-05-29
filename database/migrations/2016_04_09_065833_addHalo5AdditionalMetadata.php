<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddHalo5AdditionalMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_teams', function (Blueprint $table) {
            $table->tinyInteger('id', false, true);
            $table->string('name', 32);
            $table->string('color', 8);
            $table->uuid('contentId');

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halo5_teams');
    }
}
