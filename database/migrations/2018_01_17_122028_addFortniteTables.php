<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFortniteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fortnite_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('epic_id', 128);
            $table->integer('account_id', false, true)->nullable();
            $table->integer('user_id', false, true)->nullable();

            $this->types($table, 'solo');
            $this->places($table, 'solo');

            $this->types($table, 'duo');
            $this->places($table, 'duo');

            $this->types($table, 'squad');
            $this->places($table, 'squad');

            $table->timestamps();
            $table->integer('inactiveCounter', false, true)->default(0);
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fortnite_stats');
    }

    private function types(Blueprint $table, string $type)
    {
        $table->timestamp($type . '_lastmodified');
        $table->integer($type . '_kills', false, true);
        $table->integer($type . '_matchesplayed', false, true);
        $table->integer($type . '_score', false, true);
        $table->integer($type . '_minutesplayed', false, true);
    }

    private function places(Blueprint $table, string $type)
    {
        $table->integer($type . '_top1', false, true);
        $table->integer($type . '_top3', false, true);
        $table->integer($type . '_top5', false, true);
        $table->integer($type . '_top6', false, true);
        $table->integer($type . '_top10', false, true);
        $table->integer($type . '_top12', false, true);
        $table->integer($type . '_top25', false, true);
    }
}
