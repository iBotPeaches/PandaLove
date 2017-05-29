<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddNewFieldsToEventTableHalo5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_match_events', function (Blueprint $table) {
            $table->integer('seconds_held_as_primary')->nullable();
            $table->mediumInteger('shots_fired', false, true)->nullable();
            $table->mediumInteger('shots_landed', false, true)->nullable();
            $table->tinyInteger('round_index', false, true)->nullable();
        });

        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `death_owner` `death_owner` TINYINT(3) UNSIGNED NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `death_type` `death_type` TINYINT(3) UNSIGNED NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `killer_type` `killer_type` TINYINT(3) UNSIGNED NULL; ');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `killer_attachments` `killer_attachments` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');

        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `killer_x` `killer_x` DOUBLE(6,3) NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `killer_y` `killer_y` DOUBLE(6,3) NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `killer_z` `killer_z` DOUBLE(6,3) NULL;');

        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `victim_x` `victim_x` DOUBLE(6,3) NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `victim_y` `victim_y` DOUBLE(6,3) NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `victim_z` `victim_z` DOUBLE(6,3) NULL;');

        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `victim_type` `victim_type` TINYINT(3) UNSIGNED NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `victim_attachments` `victim_attachments` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `event_name` `event_name` TINYINT(2) UNSIGNED NOT NULL;');
        \DB::statement('ALTER TABLE `halo5_match_events` CHANGE `distance` `distance` DOUBLE(6,3) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_match_events', function (Blueprint $table) {
            $table->dropColumn('seconds_held_as_primary');
            $table->dropColumn('shots_fired');
            $table->dropColumn('shots_landed');
            $table->dropColumn('round_index');
        });
    }
}
