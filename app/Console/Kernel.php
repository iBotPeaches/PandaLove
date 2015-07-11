<?php namespace PandaLove\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Onyx\Destiny\Client;
use Onyx\Destiny\Objects\Game;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->call(function() {
            $game = Game::where('version', '<', config('app.version'))
                ->limit(1)
                ->first();

            $client = new Client();
            $client->updateGame($game->instanceId);

        })->everyFiveMinutes();
	}

}
