<?php

namespace PandaLove\Console;

use Bugsnag\BugsnagLaravel\Commands\DeployCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use PandaLove\Console\Commands\Calendar\alertSender;
use PandaLove\Console\Commands\Destiny\riseCountdown;
use PandaLove\Console\Commands\Destiny\updatePandas;
use PandaLove\Console\Commands\Halo5\addMatchEvent;
use PandaLove\Console\Commands\Halo5\batchHalo5Metadata;
use PandaLove\Console\Commands\Halo5\updateCsrs;
use PandaLove\Console\Commands\Halo5\updateEnemies;
use PandaLove\Console\Commands\Halo5\updateGametypes;
use PandaLove\Console\Commands\Halo5\updateHistoricStats;
use PandaLove\Console\Commands\Halo5\updateImpulses;
use PandaLove\Console\Commands\Halo5\updateMaps;
use PandaLove\Console\Commands\Halo5\updateMedals;
use PandaLove\Console\Commands\Halo5\updatePlaylists;
use PandaLove\Console\Commands\Halo5\updateRanks;
use PandaLove\Console\Commands\Halo5\updateSeasons;
use PandaLove\Console\Commands\Halo5\updateTeams;
use PandaLove\Console\Commands\Halo5\updateVehicles;
use PandaLove\Console\Commands\Halo5\updateWeapons;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // calendar
        alertSender::class,

        // destiny
        updatePandas::class,
        riseCountdown::class,

        // overwatch
        \PandaLove\Console\Commands\Overwatch\updatePandas::class,

        // halo 5
        updateMedals::class,
        updateCsrs::class,
        updatePlaylists::class,
        updateSeasons::class,
        \PandaLove\Console\Commands\Halo5\updatePandas::class,
        updateWeapons::class,
        updateHistoricStats::class,
        addMatchEvent::class,
        updateGametypes::class,
        updateMaps::class,
        updateRanks::class,
        updateTeams::class,
        updateEnemies::class,
        updateImpulses::class,
        updateVehicles::class,
        batchHalo5Metadata::class,
        riseCountdown::class,

        // system
        DeployCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    }
}
