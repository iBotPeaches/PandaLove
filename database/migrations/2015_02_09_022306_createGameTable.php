<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function(Blueprint $table) {
			$table->increments('id');
			$table->string('instanceId');
			$table->string('referenceId');
			$table->enum('type', ['Raid', 'Flawless', 'PVP']);
			$table->dateTime('occurredAt');
			$table->tinyInteger('raidTuesday', false, true);
			$table->mediumInteger('timeTookInMinutes', false, true);

			for ($i = 1; $i <= 6; $i++)
			{
				$table->string($i . "_player");
				$table->tinyInteger($i . "_level", false, true);
				$table->string($i . "_class", 16);
				$table->string($i . "_emblem", 32);

				$table->mediumInteger($i . "_assists", false, true);
				$table->mediumInteger($i . "_deaths", false, true);
				$table->mediumInteger($i . "_kills", false, true);
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('games');
	}

}
