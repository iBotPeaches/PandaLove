<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixDuplicatePlaylistsInSeason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('TRUNCATE halo5_season_playlists"');
        
        Schema::table('halo5_season_playlists', function (Blueprint $table)
        {
            $table->unique(['seasonId', 'playlistId'], 'halo5_seasons_playlists_seasonid_playlistid_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_season_playlists', function (Blueprint $table)
        {
            $table->dropIndex('halo5_seasons_playlists_seasonid_playlistid_unique');
        });
    }
}
