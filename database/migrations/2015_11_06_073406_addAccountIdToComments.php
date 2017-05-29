<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAccountIdToComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('account_id', false, true);
        });

        // Loop all comments, find account_id via membershipId
        \Onyx\Objects\Comment::chunk(100, function ($comments) {
            foreach ($comments as $comment) {
                $data = \Onyx\Destiny\Objects\Data::where('membershipId', $comment->membershipId)->first();

                if ($data instanceof \Onyx\Destiny\Objects\Data) {
                    $comment->account_id = $data->account_id;
                    $comment->save();
                }
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
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('account_id');
        });
    }
}
