<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TweakH5DataPart2 extends Migration
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
            $table->string('highest_CsrPlaylistId')->nullable();
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
            $table->dropColumn('highest_CsrPlaylistId');
        });
    }
}
