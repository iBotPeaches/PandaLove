<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanupForIssue60 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->dropForeign('halo5_match_events_killer_weapon_id_foreign');
            $table->dropForeign('halo5_match_events_victim_stock_id_foreign');
        });

        Schema::table('halo5_weapons', function (Blueprint $table)
        {
            $table->string('uuid', 12)->change();
        });

        Schema::table('halo5_enemies', function (Blueprint $table)
        {
            $table->string('id', 12)->change();
        });

        \Onyx\Halo5\Objects\MatchEvent::chunk(200, function($events)
        {
            /* @var $events \Onyx\Halo5\Objects\MatchEvent[] */
            foreach ($events as $event)
            {
                $event->killer_x = round($event->killer_x, 3);
                $event->killer_y = round($event->killer_y, 3);
                $event->killer_z = round($event->killer_z, 3);
                $event->victim_x = round($event->victim_x, 3);
                $event->victim_y = round($event->victim_y, 3);
                $event->victim_z = round($event->victim_z, 3);
                $event->distance = round($event->distance, 3);
                $event->save();
            }
        });

        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->string('killer_weapon_id', 12)->nullable()->change();
            $table->string('victim_stock_id', 12)->nullable()->change();
            $table->smallInteger('seconds_since_start', false, true)->change();
        });

        DB::statement('ALTER TABLE `halo5_match_events` CHANGE `killer_x` `killer_x` DOUBLE(6,3) NOT NULL;');
        DB::statement('ALTER TABLE `halo5_match_events` CHANGE `killer_y` `killer_y` DOUBLE(6,3) NOT NULL;');
        DB::statement('ALTER TABLE `halo5_match_events` CHANGE `killer_z` `killer_z` DOUBLE(6,3) NOT NULL;');

        DB::statement('ALTER TABLE `halo5_match_events` CHANGE `victim_x` `victim_x` DOUBLE(6,3) NOT NULL;');
        DB::statement('ALTER TABLE `halo5_match_events` CHANGE `victim_y` `victim_y` DOUBLE(6,3) NOT NULL;');
        DB::statement('ALTER TABLE `halo5_match_events` CHANGE `victim_z` `victim_z` DOUBLE(6,3) NOT NULL;');

        DB::statement('ALTER TABLE `halo5_match_events` CHANGE `distance` `distance` DOUBLE(6,3) NOT NULL;');

        Schema::table('halo5_match_events', function (Blueprint $table)
        {
            $table->foreign('killer_weapon_id')->references('uuid')->on('halo5_weapons');
            $table->foreign('victim_stock_id')->references('id')->on('halo5_enemies');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
