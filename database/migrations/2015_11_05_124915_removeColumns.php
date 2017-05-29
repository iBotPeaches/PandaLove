<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['membershipId', 'clanName', 'clanTag', 'glimmer', 'grimoire', 'legendary_marks',
                'character_1', 'character_2', 'character_3', 'inactiveCounter', ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('membershipId', 64)->unique();
            $table->string('clanName', 32)->nullable();
            $table->string('clanTag', 6)->nullable();
            $table->mediumInteger('glimmer', false, true);
            $table->mediumInteger('grimoire', false, true);
            $table->mediumInteger('legendary_marks', false, true);
            $table->string('character_1', 32)->nullable();
            $table->string('character_2', 32)->nullable();
            $table->string('character_3', 32)->nullable();
            $table->tinyInteger('inactiveCounter', false, true);
            $table->timestamps();
        });
    }
}
