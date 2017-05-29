<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTakenKingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->string('emote', 32);
            $table->string('artifact', 32);
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->integer('legendary_marks', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('emote');
            $table->dropColumn('artifact');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('legendary_marks');
        });
    }
}
