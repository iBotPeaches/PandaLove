<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataToPlaylistData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_playlists_data', function(Blueprint $table)
        {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_playlists_data', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });
    }
}
