<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMembershipIdBackToAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function(Blueprint $table)
        {
            $table->string('destiny_membershipId', 64);
            $table->dropIndex('accounts_id_index');
            $table->index('destiny_membershipId');
        });

        \Onyx\Account::chunk(100, function($accounts)
        {
            foreach ($accounts as $account)
            {
                $data = \Onyx\Destiny\Objects\Data::where('account_id', $account->id)->first();
                $account->destiny_membershipId = $data->membershipId;
                $account->save();
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
        Schema::table('accounts', function(Blueprint $table)
        {
            $table->index('id');
            $table->dropColumn('destiny_membershipId');
            $table->dropIndex('accounts_destiny_membershipId_index');
        });
    }
}
