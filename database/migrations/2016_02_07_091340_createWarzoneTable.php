<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarzoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_warzone', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('account_id', false, true);
            $table->integer('totalKills', false, true);
            $table->integer('totalHeadshots', false, true);
            $table->integer('totalDeaths', false, true);
            $table->integer('totalAssists', false, true);
            $table->integer('totalGames', false, true);
            $table->integer('totalGamesWon', false, true);
            $table->integer('totalGamesLost', false, true);
            $table->integer('totalGamesTied', false, true);
            $table->integer('totalTimePlayed', false, true);
            $table->integer('totalPiesEarned', false, true);

            $table->text('medals');
            $table->text('weapons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
