<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesForDestinyGames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('destiny_games', function (Blueprint $table)
        {
            $table->index('type');
            $table->index('raidTuesday');
            $table->index('passageId');
        });

        Schema::table('destiny_game_players', function (Blueprint $table)
        {
            $table->index('game_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('destiny_games', function (Blueprint $table)
        {
            $table->dropIndex('destiny_games_type_index');
            $table->dropIndex('destiny_games_raidtuesday_index');
            $table->dropIndex('destiny_games_passageid_index');
        });

        Schema::table('destiny_game_players', function (Blueprint $table)
        {
            $table->dropIndex('destiny_game_players_game_id_index');
        });
    }
}
