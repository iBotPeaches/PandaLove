<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCalendarTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('attendees', 'calendar_attendees');
        Schema::rename('game_events', 'calendar_game_events');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('calendar_attendees', 'attendees');
        Schema::rename('calendar_game_events', 'game_events');
    }
}
