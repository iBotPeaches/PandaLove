<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddScalesToMaps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_maps', function (Blueprint $table) {
            $table->smallInteger('x_orig')->nullable();
            $table->smallInteger('y_orig')->nullable();
            $table->decimal('x_scale', 6, 4)->nullable();
            $table->decimal('y_scale', 6, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_maps', function (Blueprint $table) {
            $table->dropColumn('x_orig');
            $table->dropColumn('y_orig');
            $table->dropColumn('x_scale');
            $table->dropColumn('y_scale');
        });
    }
}
