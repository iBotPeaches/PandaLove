<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->string('clanName', 32);
			$table->string('clanTag', 6);
			$table->mediumInteger('glimmer', false, true);
			$table->mediumInteger('grimoire', false, true);
			$table->string('character_1', 32)->nullable();
			$table->string('character_2', 32)->nullable();
			$table->string('character_3', 32)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->dropColumn('clanName');
			$table->dropColumn('clanTag');
			$table->dropColumn('glimmer');
			$table->dropColumn('grimoire');
			$table->dropColumn('character_1');
			$table->dropColumn('character_2');
			$table->dropColumn('character_3');
		});
	}

}
