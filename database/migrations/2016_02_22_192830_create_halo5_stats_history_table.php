<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHalo5StatsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_stats_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id', false, true);
            $table->double('arena_kd', 5, 2);
            $table->double('arena_kda', 5, 2);
            $table->integer('arena_total_games', false, true);
            $table->double('warzone_kd', 5, 2);
            $table->double('warzone_kda', 5, 2);
            $table->integer('warzone_total_games', false, true);
            $table->dateTime('date');

            $table->index('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('halo5_stats_history');
    }
}
