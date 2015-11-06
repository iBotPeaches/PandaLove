<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountIdToGamePlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_players', function(Blueprint $table)
        {
            $table->integer('account_id', false, true);
        });

        // Loop all game_players, find the account_id via membershipId on DestinyData
        \Onyx\Destiny\Objects\GamePlayer::chunk(100, function($players)
        {
            foreach ($players as $player)
            {
                $data = \Onyx\Destiny\Objects\Data::where('membershipId', $player->membershipId)->first();

                if ($data instanceof \Onyx\Destiny\Objects\Data)
                {
                    $player->account_id = $data->account_id;
                    $player->save();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_players', function(Blueprint $table)
        {
            $table->dropColumn('account_id');
        });
    }
}
