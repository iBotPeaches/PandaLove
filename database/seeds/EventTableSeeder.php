<?php

use Illuminate\Database\Seeder;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('game_events')->insert([
            'title' => 'Test Event',
            'type' => 'ToO',
            'start' => \Carbon\Carbon::now()->addDay()
        ]);
    }
}
