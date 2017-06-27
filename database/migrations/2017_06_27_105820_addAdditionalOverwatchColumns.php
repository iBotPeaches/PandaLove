<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAdditionalOverwatchColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overwatch_stats', function (Blueprint $table) {
            $table->bigInteger('shots_fired', false, true);
            $table->integer('ultimates_earned', false, true);
            $table->decimal('time_holding_ultimate', 8, 2);
            $table->bigInteger('damage_blocked', false, true);
            $table->integer('ultimates_used', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overwatch_stats', function (Blueprint $table) {
            $table->dropColumn([
                'shots_fired',
                'ultimates_earned',
                'time_holding_ultimate',
                'damage_blocked',
                'ultimates_used',
            ]);
        });
    }
}
