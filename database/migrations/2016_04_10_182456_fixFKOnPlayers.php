<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class FixFKOnPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_matches_players', function (Blueprint $table) {
            $table->dropForeign('halo5_matches_players_csrtier_foreign');
        });

        Schema::table('halo5_matches_players', function (Blueprint $table) {
            $table->foreign('CsrDesignationId')->references('designationId')->on('halo5_csrs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_matches_players', function (Blueprint $table) {
            $table->dropForeign('halo5_matches_players_csrdesignationid_foreign');
        });

        Schema::table('halo5_matches_players', function (Blueprint $table) {
            $table->foreign('CsrTier')->references('designationId')->on('halo5_csrs');
        });
    }
}
