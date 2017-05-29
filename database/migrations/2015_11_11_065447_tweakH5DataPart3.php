<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class TweakH5DataPart3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halo5_csrs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('bannerUrl');
            $table->json('tiers');
            $table->mediumInteger('designationId', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halo5_csrs');
    }
}
