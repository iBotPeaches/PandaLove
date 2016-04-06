<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HeatmapStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_weapons', function(Blueprint $table)
        {
            $table->unique('uuid');
        });

        Schema::create('halo5_matches', function (Blueprint $table)
        {
            $table->uuid('uuid')->unique();

            // @todo we need match pages, with the "Enhanced" Heatmap view.
            // PostGameCarnage is different per Custom, Warzone, Arena (Screw Campaign)
            // All fields common to those 3 should be here, then a halo5_match_players table
            // where we iterate players in the game
            // If needed another table like halo5_matches_arena / halo5_matches_warzone
            // can be added to support custom per game values.
            // If per player values needed, just make them NULLABLE and add them to halo5_match_players
            // Other types will ignore what they don't need.
        });

        Schema::create('halo5_match_events', function (Blueprint $table)
        {
            $table->uuid('uuid')->unique();
            $table->uuid('game_id');

            $table->tinyInteger('death_owner', false, true);
            $table->tinyInteger('death_type', false, true);

            $table->integer('killer', false, true);
            $table->tinyInteger('killer_type', false, true);
            $table->json('killer_attachments'); // attachments for killer weapons
            $table->string('killer_weapon', 32)->nullable();
            $table->double('killer_x', 12, 8);
            $table->double('killer_y', 12, 8);
            $table->double('killer_z', 12, 8);

            $table->integer('victim', false, true);
            $table->tinyInteger('victim_type', false, true);
            $table->json('victim_attachments'); // attachments for killer weapons
            $table->string('victim_weapon', 32)->nullable();
            $table->double('victim_x', 12, 8);
            $table->double('victim_y', 12, 8);
            $table->double('victim_z', 12, 8);

            $table->tinyInteger('event_name', false, true);
            $table->integer('seconds_since_start');

            $table->float('distance', 8, 2);

            $table->index('killer_weapon');
            $table->index('victim_weapon');

            $table->foreign('game_id')->references('uuid')->on('halo5_matches');
            $table->foreign('killer')->references('id')->on('accounts');
            $table->foreign('victim')->references('id')->on('accounts');
            $table->foreign('killer_weapon')->references('uuid')->on('halo5_weapons');
            $table->foreign('victim_weapon')->references('uuid')->on('halo5_weapons');
        });

        Schema::create('halo5_match_event_assists', function (Blueprint $table)
        {
            $table->uuid('uuid')->unique();
            $table->uuid('match_event');
            $table->integer('account_id', false, true);

            $table->foreign('match_event')->references('uuid')->on('halo5_match_events');
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
        Schema::dropIfExists('halo5_match_event_assists');
        Schema::dropIfExists('halo5_match_events');
        Schema::dropIfExists('halo5_matches');

        Schema::table('halo5_weapons', function(Blueprint $table)
        {
            $table->dropIndex('halo5_weapons_uuid_unique');
        });
    }
}
