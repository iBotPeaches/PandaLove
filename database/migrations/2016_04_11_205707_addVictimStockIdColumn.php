<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVictimStockIdColumn extends Migration
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
            $table->dropForeign('halo5_match_events_victim_weapon_id_foreign');
            $table->renameColumn('victim_weapon_id', 'victim_stock_id');
        });

        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->index('victim_stock_id');

            $table->foreign('victim_stock_id')->references('id')->on('halo5_enemies');
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
