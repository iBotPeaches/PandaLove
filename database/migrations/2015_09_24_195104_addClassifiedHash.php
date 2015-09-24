<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Onyx\Destiny\Objects\Hash;

class AddClassifiedHash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Hash::create([
            'hash' => '9999999999',
            'title' => 'Classified',
            'description' => 'This item is classified. It is hidden from the API. The true origins are unknown.',
            'extra' => null,
            'extraSecondary' => null
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Hash::where('hash', '9999999999')->delete();
    }
}
