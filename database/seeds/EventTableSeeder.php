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
        if (\App::environment() != 'production')
        {
            DB::table('game_events')->truncate();
        }

        DB::table('game_events')->insert([
            'title' => 'Trials Run',
            'type' => 'ToO',
            'start' => \Carbon\Carbon::now()->addDay()
        ]);

        DB::table('game_events')->insert([
            'title' => 'Crota Normal',
            'type' => 'Raid',
            'start' => \Carbon\Carbon::now()->addDay(4)
        ]);

        DB::table('game_events')->insert([
            'title' => 'Skolas',
            'type' => 'PoE',
            'start' => \Carbon\Carbon::now()->addDay(5)
        ]);

        DB::table('game_events')->insert([
            'title' => 'Control',
            'type' => 'PVP',
            'start' => \Carbon\Carbon::now()->addDay(3)
        ]);

        DB::table('game_events')->insert([
            'title' => 'FLAWLESS TIME - Crota',
            'type' => 'Flawless',
            'start' => \Carbon\Carbon::now()->addDay(8)
        ]);
    }
}
