<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('characters', function(Blueprint $table)
        {
            $table->integer('next_level_exp');
            $table->integer('progress_exp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('characters', function(Blueprint $table)
        {
            $table->dropColumn('next_level_exp');
            $table->dropColumn('progress_exp');
        });
    }
}
