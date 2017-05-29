<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class TweakH5DataPart4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_playlists', function (Blueprint $table) {
            $table->string('imageUrl')->nullable();
            $table->boolean('isActive');
            $table->enum('gameMode', ['Arena', 'Campaign', 'Custom', 'Warzone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_playlists', function (Blueprint $table) {
            $table->dropColumn(['imageUrl', 'isActive', 'gameMode']);
        });
    }
}
