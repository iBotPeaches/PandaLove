<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TweakFieldNamingInEventMatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->dropForeign('halo5_match_events_killer_foreign');
            $table->dropForeign('halo5_match_events_victim_foreign');
            $table->dropForeign('halo5_match_events_killer_weapon_foreign');
            $table->dropForeign('halo5_match_events_victim_weapon_foreign');
            $table->dropIndex('halo5_match_events_killer_weapon_index');
            $table->dropIndex('halo5_match_events_victim_weapon_index');

            $table->renameColumn('victim', 'victim_id');
            $table->renameColumn('killer', 'killer_id');
            $table->renameColumn('victim_weapon', 'victim_weapon_id');
            $table->renameColumn('killer_weapon', 'killer_weapon_id');
        });

        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->index('victim_weapon_id');
            $table->index('killer_weapon_id');

            $table->foreign('killer_id')->references('id')->on('accounts');
            $table->foreign('victim_id')->references('id')->on('accounts');
            $table->foreign('killer_weapon_id')->references('uuid')->on('halo5_weapons');
            $table->foreign('victim_weapon_id')->references('uuid')->on('halo5_weapons');
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
