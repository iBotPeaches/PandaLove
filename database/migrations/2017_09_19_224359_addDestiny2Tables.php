<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDestiny2Tables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destiny2_characters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('characterId', 64);
            $table->dateTime('lastPlayed');
            $table->integer('minutesPlayedTotal');
            $table->smallInteger('light');
            $table->smallInteger('max_light');

            $table->string('raceHash', 16);
            $table->string('genderHash', 16);
            $table->string('classHash', 16);

            $table->string('emblemPath', 255);
            $table->string('backgroundPath', 255);
            $table->string('emblemHash', 12);
            $table->tinyInteger('level');

            $table->index('characterId');
        });

        Schema::create('destiny2_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id', false, true);
            $table->string('membershipId', 64);
            $table->string('character_1', 64)->nullable();
            $table->string('character_2', 64)->nullable();
            $table->string('character_3', 64)->nullable();
            $table->integer('inactiveCounter', false, true);
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('character_1')->references('characterId')->on('destiny2_characters')->onDelete('cascade');
            $table->foreign('character_2')->references('characterId')->on('destiny2_characters')->onDelete('cascade');
            $table->foreign('character_3')->references('characterId')->on('destiny2_characters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destiny2_characters');
        Schema::dropIfExists('destiny2_data');
    }
}
