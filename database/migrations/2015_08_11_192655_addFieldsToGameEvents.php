<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldsToGameEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_events', function (Blueprint $table) {
            $table->tinyInteger('max_players', false, true);
        });

        Schema::create('attendees', function (Blueprint $table) {
            $table->integer('game_id', false, true);
            $table->string('membershipId', 64);
            $table->string('characterId', 64);
            $table->integer('account_id', false, true);
            $table->integer('user_id', false, true);

            $table->boolean('attended')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_events', function (Blueprint $table) {
            $table->dropColumn('max_players');
        });

        Schema::dropIfExists('attendees');
    }
}
