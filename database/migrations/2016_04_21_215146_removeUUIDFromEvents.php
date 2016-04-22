<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUUIDFromEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->emptyEvents();

        Schema::table('halo5_matches', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
        });

        Schema::table('halo5_match_event_assists', function (Blueprint $table)
        {
            $table->dropForeign('halo5_match_event_assists_match_event_foreign');

            $table->dropColumn('match_event');
            $table->dropColumn('uuid');
        });

        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->dropForeign('halo5_match_events_game_id_foreign');

            $table->dropColumn('uuid');
            $table->dropColumn('game_id');

            $table->dropColumn('seconds_held_as_primary');
        });

        Schema::table('halo5_matches_players', function (Blueprint $table)
        {
            $table->dropForeign('halo5_matches_players_game_id_foreign');
            $table->dropForeign('halo5_matches_players_team_id_foreign');
            $table->dropColumn('uuid');
            $table->dropColumn('game_id');
            $table->dropColumn('team_id');
        });

        Schema::table('halo5_matches_teams', function (Blueprint $table)
        {
            $table->dropForeign('halo5_matches_teams_game_id_foreign');
            $table->dropColumn('uuid');
            $table->dropColumn('game_id');
            $table->dropColumn('key');
        });

        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('game_id', false, true);
            $table->smallInteger('seconds_held_as_primary', false, true);

            $table->foreign('game_id')->references('id')->on('halo5_matches');
        });

        Schema::table('halo5_match_event_assists', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('match_event', false, true);
            $table->foreign('match_event')->references('id')->on('halo5_match_events');
        });

        Schema::table('halo5_matches_teams', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('game_id', false, true);
            $table->string('key', 24);

            $table->index('key');

            $table->foreign('game_id')->references('id')->on('halo5_matches');
        });

        Schema::table('halo5_matches_players', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('game_id', false, true);
            $table->string('team_id', 24);
            
            $table->index('team_id');

            $table->foreign('game_id')->references('id')->on('halo5_matches');
            $table->foreign('team_id')->references('key')->on('halo5_matches_teams');
        });
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

    private function emptyEvents()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('halo5_match_event_assists')->truncate();
        \DB::table('halo5_match_events')->truncate();
        \DB::table('halo5_matches_players')->truncate();
        \DB::table('halo5_matches_teams')->truncate();
        \DB::table('halo5_matches');

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
