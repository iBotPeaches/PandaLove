<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CleanupComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('membershipId', 'destiny_membershipId');
            $table->renameColumn('characterId', 'destiny_characterId');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->string('destiny_membershipId', 64)->nullable()->change();
            $table->string('destiny_characterId', 64)->nullable()->change();
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
            $table->renameColumn('destiny_membershipId', 'membershipId');
            $table->renameColumn('destiny_characterId', 'characterId');
        });
    }
}
