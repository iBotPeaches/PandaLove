<?php

use Illuminate\Database\Migrations\Migration;

class MakeNameNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `halo5_maps` CHANGE `name` `name` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
