<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPassageDay extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function(Blueprint $table)
        {
            $table->tinyInteger('passageId', false, true)->default(0);
            DB::statement("ALTER TABLE games CHANGE COLUMN type type ENUM('Raid', 'Flawless', 'PVP', 'PoE', 'ToO')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function(Blueprint $table)
        {
            $table->dropColumn('passageId');
            DB::statement("ALTER TABLE games CHANGE COLUMN type type ENUM('Raid', 'Flawless', 'PVP', 'PoE')");
        });
    }

}
