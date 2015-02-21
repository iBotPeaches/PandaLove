<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('comments', function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('comment');
            $table->timestamps();
            $table->string('membershipId', 64);
            $table->string('characterId', 64);
            $table->morphs('commentable');
            $table->integer('parent_comment_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
