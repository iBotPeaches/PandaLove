<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TweakH5Data extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halo5_data', function(Blueprint $table)
        {
            $table->dropColumn('highestCsr');

            // highest csr
            $table->tinyInteger('highest_CsrTier', false, true);
            $table->tinyInteger('highest_CsrDesignationId', false, true);
            $table->integer('highest_Csr', false, true);
            $table->integer('highest_percentNext', false, true);
            $table->integer('highest_rank', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_data', function(Blueprint $table)
        {
            $table->mediumInteger('highestCsr', false, true)->nullable();

            $table->dropColumn(array('highest_CsrTier', 'highest_CsrDesignationId', 'highest_Csr', 'highest_percentNext', 'highest_rank'));
        });
    }
}
