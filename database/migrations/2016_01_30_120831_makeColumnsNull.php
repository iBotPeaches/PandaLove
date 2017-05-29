<?php

use Illuminate\Database\Migrations\Migration;

class MakeColumnsNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `halo5_data` CHANGE `highest_CsrTier` `highest_CsrTier` TINYINT(3) UNSIGNED NULL, CHANGE `highest_CsrDesignationId` `highest_CsrDesignationId` TINYINT(3) UNSIGNED NULL, CHANGE `highest_Csr` `highest_Csr` INT(10) UNSIGNED NULL, CHANGE `highest_percentNext` `highest_percentNext` INT(10) UNSIGNED NULL, CHANGE `highest_rank` `highest_rank` INT(10) UNSIGNED NULL, CHANGE `highest_CsrPlaylistId` `highest_CsrPlaylistId` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
