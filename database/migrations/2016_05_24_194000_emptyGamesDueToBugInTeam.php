<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmptyGamesDueToBugInTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->emptyEvents();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->emptyEvents();
    }

    private function emptyEvents()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('halo5_match_event_assists')->truncate();
        \DB::table('halo5_match_events')->truncate();
        \DB::table('halo5_matches_players')->truncate();
        \DB::table('halo5_matches_teams')->truncate();
        \DB::table('halo5_matches')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
