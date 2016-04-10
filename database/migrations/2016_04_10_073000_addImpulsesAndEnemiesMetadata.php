<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImpulsesAndEnemiesMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_enemies', function (Blueprint $table)
        {
            $table->string('id')->unique();
            $table->uuid('contentId');
            $table->string('name', 32);
            $table->string('faction', 32);
            $table->string('description')->nullable();

            $table->primary('id');
            $table->index('contentId');
        });

        Schema::create('halo5_impulses', function (Blueprint $table)
        {
            $table->string('id')->unique();
            $table->uuid('contentId');
            
            $table->string('name', 32);

            $table->primary('id');
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
        Schema::dropIfExists('halo5_enemies');
        Schema::dropIfExists('halo5_impulses');
    }
}
