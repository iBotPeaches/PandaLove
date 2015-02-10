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
			$table->boolean('isHard');
			$table->enum('type', ['Raid', 'Flawless', 'PVP'])->nullable();
			$table->dateTime('occurredAt');
			$table->tinyInteger('raidTuesday', false, true);
			$table->mediumInteger('timeTookInSeconds', false, true);
		});

		Schema::create('game_players', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('game_id', false, true);

			$table->string('membershipId', 64);
			$table->string('characterId', 64);

			$table->tinyInteger('level', false, true);
			$table->string('class', 16);
			$table->string('emblem', 32);
			$table->mediumInteger('assists', false, true);
			$table->mediumInteger('deaths', false, true);
			$table->mediumInteger('kills', false, true);

			$table->boolean('completed');
			$table->integer('secondsPlayed', false, true);
			$table->float('averageLifespan');
		});

		Schema::table('hashes', function(Blueprint $table)
		{
			$table->unique('hash');
			$table->text('extraThird');
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
		Schema::drop('game_players');

		Schema::table('hashes', function(Blueprint $table)
		{
			$table->dropUnique('hashes_hash_unique');
			$table->dropColumn('extraThird');
		});
	}

}
