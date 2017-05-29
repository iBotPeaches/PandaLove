<?php

use Illuminate\Database\Migrations\Migration;

class CleanupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\Onyx\Halo5\Objects\MatchPlayer::all() as $item) {
            $item->delete();
        }

        foreach (\Onyx\Halo5\Objects\MatchTeam::all() as $item) {
            $item->delete();
        }

        foreach (\Onyx\Halo5\Objects\Match::all() as $item) {
            $item->delete();
        }

        foreach (\Onyx\Halo5\Objects\MatchEventAssist::all() as $item) {
            $item->delete();
        }

        foreach (\Onyx\Halo5\Objects\MatchEvent::all() as $item) {
            $item->delete();
        }

        \Onyx\Halo5\Objects\PlaylistData::where('created_at', '>=', new \Carbon\Carbon('2016-04-08'))->delete();
        \Onyx\Destiny\Objects\Data::where('created_at', '>=', new \Carbon\Carbon('2016-04-08'))->delete();
        \Onyx\Halo5\Objects\Data::where('created_at', '>=', new \Carbon\Carbon('2016-04-08'))->delete();
        \Onyx\Account::where('created_at', '>=', new \Carbon\Carbon('2016-04-08'))->delete();
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
