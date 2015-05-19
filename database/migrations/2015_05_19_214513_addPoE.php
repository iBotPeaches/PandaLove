<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoE extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE games CHANGE COLUMN type type ENUM('Raid', 'Flawless', 'PVP', 'PoE')");
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement("ALTER TABLE games CHANGE COLUMN type type ENUM('Raid', 'Flawless', 'PVP')");
	}

}
