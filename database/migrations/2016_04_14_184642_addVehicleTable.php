<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->emptyEvents();

        Schema::table('halo5_enemies', function (Blueprint $table)
        {
            $table->string('id', 12)->change();
        });

        Schema::table('halo5_weapons', function (Blueprint $table)
        {
            $table->string('id', 12)->change();
        });

        Schema::create('halo5_vehicles', function (Blueprint $table)
        {
            $table->string('uuid', 12);
            $table->uuid('contentId');
            $table->string('name', 64)->nullable();
            $table->string('description', 64)->nullable();
            $table->boolean('useableByPlayer');

            $table->primary('uuid');
            $table->index('contentId');
        });

        // Note in the future. This is horrible, but for a reason
        // After this post - https://www.halowaypoint.com/en-us/forums/01b3ca58f06c4bd4ad074d8794d2cf86/topics/killerstockid-showing-0-for-each-entry/23b7931b-ee56-41d4-88ef-b10a0991a68c/posts
        // As read `killerWeaponStockId` corresponds to either the Enemy/Weapon or Vehicle Metadata endpoint
        // This means that a single value can be mapped to either 3 tables.
        // This means a new linker table with composite keys would be needed to do this
        // However, this makes eager loading quite weird becoming `match.events.victim_stock.item`
        // So I decided to just create `halo5_event_metadata` and its contents are the non unique fields
        // of the Vehicle/Enemy/Weapon tables.
        Schema::create('halo5_event_metadata', function (Blueprint $table)
        {
            $table->string('uuid', 12);
            $table->uuid('contentId');
            $table->string('name', 64)->nullable();
            $table->string('description', 64)->nullable();
            $table->tinyInteger('type', false, true);

            $table->primary('uuid');
            $table->index('contentId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->emptyEvents();

        Schema::dropIfExists('halo5_event_metadata');
        Schema::dropIfExists('halo5_vehicles');
    }

    private function emptyEvents()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('halo5_match_event_assists')->truncate();
        \DB::table('halo5_match_events')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
