<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignReferencesOnEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->emptyEvents();
        
        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->dropForeign('halo5_match_events_killer_weapon_id_foreign');
            $table->dropForeign('halo5_match_events_victim_stock_id_foreign');

            $table->foreign('killer_weapon_id')->references('uuid')->on('halo5_event_metadata');
            $table->foreign('victim_stock_id')->references('uuid')->on('halo5_event_metadata');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->emptyEvents();

        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->dropForeign('halo5_match_events_killer_weapon_id_foreign');
            $table->dropForeign('halo5_match_events_victim_stock_id_foreign');

            $table->foreign('killer_weapon_id')->references('uuid')->on('halo5_weapons');
            $table->foreign('victim_stock_id')->references('id')->on('halo5_enemies');
        });
    }

    private function emptyEvents()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('halo5_match_event_assists')->truncate();
        \DB::table('halo5_match_events')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
