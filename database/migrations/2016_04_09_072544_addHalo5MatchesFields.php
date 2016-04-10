<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHalo5MatchesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_matches', function (Blueprint $table)
        {
            $table->uuid('map_variant')->nullable();
            $table->uuid('game_variant')->nullable();
            $table->uuid('playlist_id')->nullable(); // Playlist
            $table->uuid('map_id')->nullable(); // Map
            $table->uuid('gamebase_id')->nullable(); // GameType
            $table->uuid('season_id')->nullable(); // Season

            $table->index('playlist_id');
            $table->index('map_id');
            $table->index('gamebase_id');
            $table->index('season_id');

            $table->foreign('playlist_id')->references('contentId')->on('halo5_playlists');
            $table->foreign('map_id')->references('uuid')->on('halo5_maps');
            $table->foreign('gamebase_id')->references('uuid')->on('halo5_gametypes');
            $table->foreign('season_id')->references('contentId')->on('halo5_seasons');
            $table->boolean('isTeamGame');
        });

        Schema::create('halo5_matches_teams', function (Blueprint $table)
        {
            $table->uuid('uuid')->unique();
            $table->uuid('game_id');

            $table->string('key', 38)->unique();

            $table->tinyInteger('team_id', false, true);
            $table->integer('score', false, true);
            $table->integer('rank', false, true);
            $table->json('round_stats')->nullable();

            $table->index('key');

            $table->foreign('team_id')->references('id')->on('halo5_teams');
            $table->foreign('game_id')->references('uuid')->on('halo5_matches');
            $table->primary('uuid');
        });

        Schema::create('halo5_matches_players', function (Blueprint $table)
        {
            $table->uuid('uuid')->unique();
            $table->primary('uuid');
            $table->uuid('game_id');

            $table->integer('account_id', false, true);
            $table->string('team_id', 38)->nullable();
            $table->index('team_id');

            $table->json('killed');
            $table->json('killed_by');
            $table->json('medals');
            $table->json('enemies');
            $table->json('weapons');
            $table->json('impulses');

            $table->tinyInteger('warzone_req', false, true)->nullable();
            $table->mediumInteger('total_pies', false, true)->nullable();

            $table->tinyInteger('rank', false, true);
            $table->boolean('dnf');
            $table->integer('avg_lifestime', false, true);

            $table->integer('totalKills', false, true);
            $table->integer('totalSpartanKills', false, true);
            $table->integer('totalAiKills', false, true);
            $table->integer('totalHeadshots', false, true);
            $table->integer('totalDeaths', false, true);
            $table->integer('totalAssists', false, true);
            $table->integer('totalTimePlayed', false, true);

            $table->float('weapon_dmg', 10, 4);
            $table->integer('shots_fired', false, true);
            $table->integer('shots_landed', false, true);

            $table->integer('totalMeleeKills', false, true);
            $table->integer('totalAssassinations', false, true);
            $table->integer('totalGroundPounds', false, true);
            $table->integer('totalShoulderBash', false, true);
            $table->integer('totalGrenadeKills', false, true);
            $table->integer('totalPowerWeaponKills', false, true);

            $table->integer('totalPowerWeaponTime', false, true);

            $table->tinyInteger('spartanRank', false, true);
            $table->tinyInteger('CsrTier', false, true)->nullable();
            $table->tinyInteger('CsrDesignationId', false, true)->nullable();
            $table->integer('Csr', false, true)->nullable();
            $table->integer('percentNext', false, true)->nullable();
            $table->integer('ChampionRank', false, true)->nullable();

            $table->foreign('game_id')->references('uuid')->on('halo5_matches');
            $table->foreign('CsrTier')->references('designationId')->on('halo5_csrs');
            $table->foreign('team_id')->references('key')->on('halo5_matches_teams');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_matches', function (Blueprint $table)
        {
            $table->dropForeign('halo5_matches_gamebase_id_foreign');
            $table->dropForeign('halo5_matches_map_id_foreign');
            $table->dropForeign('halo5_matches_playlist_id_foreign');
            $table->dropForeign('halo5_matches_season_id_foreign');

            $table->dropColumn([
                'map_variant',
                'game_variant',
                'playlist_id',
                'map_id',
                'gamebase_id',
                'season_id',
                'isTeamGame'
            ]);
        });

        Schema::dropIfExists('halo5_matches_teams');

        Schema::dropIfExists('halo5_matches_players');
    }
}
