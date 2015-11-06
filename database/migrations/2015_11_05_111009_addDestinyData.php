<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDestinyData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destiny_data', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('account_id', false, true);
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

        \Onyx\Account::chunk(100, function($accounts)
        {
            foreach ($accounts as $account)
            {
                $data = new \Onyx\Destiny\Objects\Data($account->toArray());
                $data->account_id = $account->id;
                $data->save();
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
        Schema::drop('destiny_data');
    }
}
