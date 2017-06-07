<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOWTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overwatch_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id', false, true);
            $table->integer('season', false, true);

            $table->decimal('healing_done_avg', 8, 2);
            $table->decimal('deaths_avg', 8, 2);
            $table->decimal('damage_done_avg', 8, 2);
            $table->decimal('final_blows_avg', 8, 2);
            $table->decimal('eliminations_avg', 8, 2);
            $table->decimal('time_spent_on_fire_avg', 8, 2);
            $table->decimal('solo_kills_avg', 8, 2);
            $table->decimal('melee_final_blows_avg', 8, 2);
            $table->decimal('objective_kills_avg', 8, 2);
            $table->decimal('objective_time_avg', 8, 2);

            // overall
            $table->integer('ties', false, true);
            $table->integer('losses', false, true);
            $table->integer('wins', false, true);
            $table->mediumInteger('level' , false, true);
            $table->decimal('win_rate', 4, 2);
            $table->smallInteger('prestige');
            $table->integer('games', false, true);
            $table->mediumInteger('comprank', false, true);
            $table->mediumInteger('max_comprank', false, true);
            $table->string('tier', 32);
            $table->string('avatar', 200);
            $table->string('rank_image', 200);

            // stats
            $table->integer('damage_done_most_in_game', false, true);
            $table->integer('objective_kills', false, true);
            $table->decimal('time_spent_on_fire', 8, 3);
            $table->smallInteger('eliminations_most_in_game', false, true);
            $table->integer('medals_bronze', false, true);
            $table->integer('games_won', false, true);
            $table->tinyInteger('teleporter_pad_destroyed_most_in_game', false, true);
            $table->integer('final_blows', false, true);
            $table->integer('deaths', false, true);
            $table->decimal('time_spent_on_fire_most_in_game', 8, 3);
            $table->integer('games_lost', false, true);
            $table->integer('offensive_assists_most_in_game', false, true);
            $table->integer('turrets_destroyed', false, true);
            $table->decimal('objective_time_most_in_game', 5, 3);
            $table->mediumInteger('defensive_assists_most_in_game', false, true);
            $table->tinyInteger('turrets_destroyed_most_in_game', false, true);
            $table->mediumInteger('teleporter_pad_destroyed', false, true);
            $table->bigInteger('healing_done', false, true);
            $table->integer('medals_silver', false, true);
            $table->integer('multikills', false, true);
            $table->integer('medals_gold', false, true);
            $table->integer('eliminations', false, true);
            $table->integer('multikill_best', false, true);
            $table->integer('cards', false, true);
            $table->mediumInteger('objective_kills_most_in_game', false, true);
            $table->integer('offensive_assists', false, true);
            $table->integer('games_played', false, true);
            $table->integer('environmental_kills', false, true);
            $table->tinyInteger('environmental_kills_most_in_game', false, true);
            $table->decimal('kpd', 5, 2);
            $table->integer('environmental_deaths', false, true);
            $table->mediumInteger('kill_streak_best', false, true);
            $table->integer('healing_done_most_in_game', false, true);
            $table->integer('solo_kills', false, true);
            $table->integer('final_blows_most_in_game', false, true);
            $table->integer('solo_kills_most_in_game', false, true);
            $table->integer('time_played', false, true);
            $table->tinyInteger('melee_final_blow_most_in_game', false, true);
            $table->bigInteger('damage_done', false, true);
            $table->decimal('objective_time', 8, 3);
            $table->integer('medals', false, true);
            $table->integer('games_tied', false, true);
            $table->integer('melee_final_blows', false, true);
            $table->integer('defensive_assists', false, true);

            $table->unique(['account_id', 'season']);
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->timestamps();
            $table->integer('inactive_counter', false, true);
        });

        Schema::create('overwatch_character_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id', false);
            $table->string('character', 10);

            $table->decimal('playtime', 5, 2);
            $table->json('data');

            $table->foreign('account_id')->references('id')->on('overwatch_stats');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overwatch_character_stats');
        Schema::dropIfExists('overwatch_stats');
    }
}
