<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPlaylistsInSeason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_season_playlists', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('seasonId');
            $table->uuid('playlistId');

            $table->index('seasonId');
            $table->index('playlistId');

            $table->foreign('seasonId')->references('contentId')->on('halo5_seasons');
            $table->foreign('playlistId')->references('contentId')->on('halo5_playlists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halo5_season_playlists');
    }
}
