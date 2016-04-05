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
        Schema::table('halo5_matches', function (Blueprint $table)
        {
            $table->uuid('uuid')->unique();

            // post game carnage report, type
        });

        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->uuid('uuid')->unique();
            $table->uuid('game_id'); // FK to halo5_matches uuid

            $table->tinyInteger('death_owner', false, true); // Enum for 0 Friendly, 1 Hostile, 2 Neutral
            $table->tinyInteger('death_type', false, true); // Enum Assassination, GroundPoound, Headshot, Melee, ShoulderBash, Weapon

            $table->integer('killer', false, true);
            $table->tinyInteger('killer_type', false, true); // Enum 0 - NONE, 1 - Player, 2 - AI
            $table->json('killer_attachments'); // attachments for killer weapons
            $table->integer('killer_weapon', false, true);
            $table->text('killer_location'); // X, Y, Z

            $table->integer('victim', false, true);
            $table->tinyInteger('victim_type', false, true); // Enum 0 - NONE, 1 - Player, 2 - AI
            $table->json('victim_attachments'); // attachments for killer weapons
            $table->integer('victim_weapon', false, true);
            $table->text('victim_location'); // X, Y, Z

            $table->string('event_name', 32)->default('Death'); // Only currently supported type
            $table->dateTime('date');
        });

        Schema::table('halo5_match_event_assists', function (Blueprint $table)
        {
            $table->uuid('uuid')->unique();
            $table->uuid('match_event');

            $table->integer('account_id', false, true);
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
}
