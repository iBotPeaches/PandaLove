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

        if (\App::environment() != 'production') {
            DB::table('calendar_game_events')->truncate();
            DB::table('calendar_attendees')->truncate();
        }

        \Onyx\Calendar\Objects\Event::create(
            [
                'title'       => 'Trials Run',
                'type'        => 'ToO',
                'max_players' => 3,
                'game'        => 'destiny',
                'start'       => \Carbon\Carbon::now()->addDay(),
            ]
        );

        \Onyx\Calendar\Objects\Event::create(
            [
                'title'       => 'Crota Normal',
                'type'        => 'Raid',
                'max_players' => 6,
                'game'        => 'destiny',
                'start'       => \Carbon\Carbon::now()->addDay(4),
            ]
        );
    }
}
