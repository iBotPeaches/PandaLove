<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('characters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('membershipId', 64);
			$table->string('characterId', 64);
			$table->dateTime('last_played');
			$table->timestamps();
			$table->integer('minutes_played', false, true);
			$table->integer('minutes_played_last_session', false, true);
			$table->tinyInteger('level', false, true);
			$table->string('race', 16);
			$table->string('gender', 16);
			$table->string('class', 16);

			$table->mediumInteger('defense', false, true);
			$table->mediumInteger('intellect', false, true);
			$table->mediumInteger('discipline', false, true);
			$table->mediumInteger('strength', false, true);
			$table->tinyInteger('light', false, true);

			$table->string('emblem', 64);
			$table->string('background', 64);

			$table->string('subclass', 32);
			$table->string('helmet', 32);
			$table->string('arms', 32);
			$table->string('chest', 32);
			$table->string('boots', 32);
			$table->string('class_item', 32);

			$table->string('primary', 32);
			$table->string('secondary', 32);
			$table->string('heavy', 32);

			$table->string('ship', 32);
			$table->string('sparrow', 32);
			$table->string('ghost', 32);

			$table->string('shader', 32);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('characters');
	}

}
