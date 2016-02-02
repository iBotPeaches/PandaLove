<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PortInactiveCounterToH5 extends Migration
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
            $table->tinyInteger('inactiveCounter', false, true)->default(0);
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
            $table->dropColumn('inactiveCounter');
        });
    }
}
