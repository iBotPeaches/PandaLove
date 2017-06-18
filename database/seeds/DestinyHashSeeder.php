<?php

use Illuminate\Database\Seeder;
use Onyx\Destiny\Objects\Hash as Hash;

class DestinyHashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('America/Chicago');

        if (\App::environment() != 'production') {
            DB::table('destiny_metadata')->truncate();
        }

        Hash::create([
            'hash'           => '9999999999',
            'title'          => 'Classified',
            'description'    => 'This item is classified. It is hidden from the API. The true origins are unknown.',
            'extra'          => null,
            'extraSecondary' => null,
        ]);
    }
}
