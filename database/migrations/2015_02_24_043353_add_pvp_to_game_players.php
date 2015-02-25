<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPvpToGamePlayers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_players', function(Blueprint $table)
        {
            $table->integer('score')->nullable();
            $table->tinyInteger('team')->nullable();
            $table->tinyInteger('standing')->nullable();
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
            $table->dropColumn('score');
            $table->dropColumn('team');
            $table->dropColumn('standing');
        });
    }

}
