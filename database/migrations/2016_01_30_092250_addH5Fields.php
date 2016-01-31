<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddH5Fields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_data', function(Blueprint $table)
        {
            $table->string('highest_CsrSeasonId')->nullable();
            $table->string('seasonId');
        });

        Schema::table('halo5_playlists_data', function(Blueprint $table)
        {
            $table->string('seasonId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_data', function(Blueprint $table)
        {
            $table->dropColumn(['highest_CsrSeasonId', 'seasonId']);
        });

        Schema::table('halo5_playlists_data', function(Blueprint $table)
        {
            $table->dropColumn(['seasonId']);
        });
    }
}
