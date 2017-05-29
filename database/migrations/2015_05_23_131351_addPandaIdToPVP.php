<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPandaIdToPVP extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pvp_games', function (Blueprint $table) {
            $table->tinyInteger('pandaId', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pvp_games', function (Blueprint $table) {
            $table->dropColumn('pandaId');
        });
    }
}
