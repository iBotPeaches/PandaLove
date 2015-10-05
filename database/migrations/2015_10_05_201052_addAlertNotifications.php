<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlertNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_events', function($table)
        {
            $table->boolean('alert_5')->default(false);
            $table->boolean('alert_15')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_events', function($table)
        {
            $table->dropColumn('alert_5');
            $table->dropColumn('alert_15');
        });
    }
}
