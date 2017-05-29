<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddH5Data extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id', false, true);
            $table->mediumInteger('highestCsr', false, true)->nullable();
            $table->integer('totalKills', false, true);
            $table->integer('totalSpartanKills', false, true); // not sure of difference
            $table->integer('totalHeadshots', false, true);
            $table->integer('totalDeaths', false, true);
            $table->integer('totalAssists', false, true);
            $table->integer('totalGames', false, true);
            $table->integer('totalGamesWon', false, true);
            $table->integer('totalGamesLost', false, true);
            $table->integer('totalGamesTied', false, true);
            $table->integer('totalTimePlayed', false, true);
            $table->tinyInteger('spartanRank', false, true);
            $table->bigInteger('Xp', false, true);

            // json stuff
            $table->json('medals');

            // urls
            $table->string('emblem');
            $table->string('spartan');
        });

        Schema::create('halo5_playlists_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id', false, true);
            $table->string('playlistId', 64);
            $table->tinyInteger('measurementMatchesLeft', false, true)->default(0);

            // highest csr
            $table->tinyInteger('highest_CsrTier', false, true);
            $table->tinyInteger('highest_CsrDesignationId', false, true);
            $table->integer('highest_Csr', false, true);
            $table->integer('highest_percentNext', false, true);
            $table->integer('highest_rank', false, true);

            // current csr
            $table->tinyInteger('current_CsrTier', false, true);
            $table->tinyInteger('current_CsrDesignationId', false, true);
            $table->integer('current_Csr', false, true);
            $table->integer('current_percentNext', false, true);
            $table->integer('current_rank', false, true);

            // per playlist stats
            $table->integer('totalKills', false, true);
            $table->integer('totalSpartanKills', false, true); // not sure of difference
            $table->integer('totalHeadshots', false, true);
            $table->integer('totalDeaths', false, true);
            $table->integer('totalAssists', false, true);
            $table->integer('totalGames', false, true);
            $table->integer('totalGamesWon', false, true);
            $table->integer('totalGamesLost', false, true);
            $table->integer('totalGamesTied', false, true);
            $table->integer('totalTimePlayed', false, true);
        });

        Schema::create('halo5_playlists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contentId')->unique();
            $table->string('name', 128);
            $table->string('description', 255);
            $table->boolean('isRanked');
        });

        Schema::create('halo5_medals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contentId')->unique();
            $table->string('name', 128);
            $table->string('description', 255);
            $table->string('classification', 32);
            $table->integer('difficulty', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('halo5_data');
        Schema::drop('halo5_playlists_data');
        Schema::drop('halo5_playlists');
        Schema::drop('halo5_medals');
    }
}
