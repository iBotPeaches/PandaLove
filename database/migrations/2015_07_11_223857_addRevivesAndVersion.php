<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRevivesAndVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->tinyInteger('version', false, true)->default(1);
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->integer('revives_given', false, true)->default(0);
            $table->integer('revives_taken', false, true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('verison');
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->dropColumn('revives_given');
            $table->dropColumn('revives_taken');
        });
    }
}
