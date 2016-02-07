<?php namespace PandaLove\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Onyx\Destiny\Client;
use Onyx\Destiny\Objects\Game;

use PandaLove\Console\Commands\updatePandas;
use PandaLove\Console\Commands\alertSender;
use PandaLove\Console\Commands\updateMedals;
use PandaLove\Console\Commands\updateCsrs;
use PandaLove\Console\Commands\updatePlaylists;
use PandaLove\Console\Commands\updateSeasons;
use PandaLove\Console\Commands\updateWeapons;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		updatePandas::class,
		alertSender::class,
		updateMedals::class,
		updateCsrs::class,
		updatePlaylists::class,
		updateSeasons::class,
		updateWeapons::class
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{

	}

}
