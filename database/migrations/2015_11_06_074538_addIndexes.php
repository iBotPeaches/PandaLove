<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->index('membershipId');
            $table->index('characterId');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->index('id');
            $table->unique('gamertag');
        });

        Schema::table('destiny_data', function (Blueprint $table) {
            $table->index('account_id');
            $table->index('clanName');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->index('instanceId');
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->index('membershipId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropIndex('characters_membershipId_index');
            $table->dropIndex('characters_characterId_index');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropIndex('accounts_id_index');
            $table->dropIndex('accounts_gamertag_unique');
        });

        Schema::table('destiny_data', function (Blueprint $table) {
            $table->dropIndex('destiny_data_account_id_index');
            $table->dropIndex('destiny_data_clanName_index');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropIndex('games_instanceId_index');
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->dropIndex('game_players_membershipId_index');
        });
    }
}
