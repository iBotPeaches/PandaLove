<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCsrPercentile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_playlists_data', function (Blueprint $table) {
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
        Schema::table('halo5_playlists_data', function (Blueprint $table) {
            $table->dropColumn('csrPercentile');
        });
    }
}
