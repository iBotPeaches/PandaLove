<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSpartanKillsToWarzone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_warzone', function (Blueprint $table) {
            $table->integer('totalSpartanKills', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_warzone', function (Blueprint $table) {
            $table->dropColumn('totalSpartanKills');
        });
    }
}
