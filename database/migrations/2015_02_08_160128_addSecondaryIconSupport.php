<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSecondaryIconSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hashes', function (Blueprint $table) {
            $table->text('extraSecondary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hashes', function (Blueprint $table) {
            $table->dropColumn('extraSecondary');
        });
    }
}
