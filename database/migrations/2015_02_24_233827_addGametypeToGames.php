<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGametypeToGames extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pvp_games', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('instanceId', 32);
            $table->string('gametype', 32)->nullable();
            $table->mediumInteger('winnerPts', false, true);
            $table->mediumInteger('loserPts', false, true);

            $table->tinyInteger('winnerId', false, true);
            $table->tinyInteger('loserId', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pvp_games');
    }
}
