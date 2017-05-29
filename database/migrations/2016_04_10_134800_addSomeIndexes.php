<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSomeIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('destiny_data', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->index('seo');
            $table->index('accountType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('destiny_data', function (Blueprint $table) {
            $table->dropIndex('destiny_data_created_at_index');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropIndex('accounts_seo_index');
            $table->dropIndex('accounts_accountType_index');
        });
    }
}
