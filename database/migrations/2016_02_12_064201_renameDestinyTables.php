<?php

use Illuminate\Database\Migrations\Migration;

class RenameDestinyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('characters', 'destiny_characters');
        Schema::rename('games', 'destiny_games');
        Schema::rename('game_players', 'destiny_game_players');
        Schema::rename('hashes', 'destiny_metadata');
        Schema::rename('pvp_games', 'destiny_pvp_games');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('destiny_characters', 'characters');
        Schema::rename('destiny_games', 'games');
        Schema::rename('destiny_game_players', 'game_players');
        Schema::rename('destiny_metadata', 'hashes');
        Schema::rename('destiny_pvp_games', 'pvp_games');
    }
}
