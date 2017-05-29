<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMapVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->emptyEvents();

        Schema::create('halo5_map_variants', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->string('name', 64);
            $table->string('map_id', 64);
            $table->text('description')->nullable();

            $table->primary('uuid');
            $table->index('map_id');
            $table->foreign('map_id')->references('contentId')->on('halo5_maps');
        });

        Schema::table('halo5_matches', function (Blueprint $table) {
            $table->index('map_variant');

            $table->foreign('map_variant')->references('uuid')->on('halo5_map_variants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_matches', function (Blueprint $table) {
            $table->dropForeign('');
        });

        Schema::dropIfExists('halo5_map_variants');
    }

    private function emptyEvents()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('halo5_match_event_assists')->truncate();
        \DB::table('halo5_match_events')->truncate();
        \DB::table('halo5_matches_players')->truncate();
        \DB::table('halo5_matches_teams')->truncate();
        \DB::table('halo5_matches')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
