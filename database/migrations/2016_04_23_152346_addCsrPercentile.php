<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCsrPercentile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_playlists_data', function (Blueprint $table)
        {
            $table->tinyInteger('csrPercentile', false, true)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_playlists_data', function (Blueprint $table)
        {
            $table->dropColumn('csrPercentile');
        });
    }
}
