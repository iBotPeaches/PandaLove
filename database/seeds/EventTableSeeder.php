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
        date_default_timezone_set('America/Chicago');

        if (\App::environment() != 'production')
        {
            DB::table('game_events')->truncate();
            DB::table('attendees')->truncate();
        }

        \Onyx\Calendar\Objects\Event::create(
            [
                'title' => 'Trials Run',
                'type' => 'ToO',
                'start' => \Carbon\Carbon::now()->addDay()
            ]
        );

        \Onyx\Calendar\Objects\Event::create(
            [
                'title' => 'Crota Normal',
                'type' => 'Raid',
                'start' => \Carbon\Carbon::now()->addDay(4)
            ]
        );
    }
}
