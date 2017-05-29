<?php

namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Onyx\Halo5\Objects\CSR;

class batchHalo5Metadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:batch-metadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls down ALL metadata (with some sleep in-between)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commands = [
            'halo5:gametypes-update',
            'halo5:playlist-update',
            'halo5:weapons-update',
            'halo5:season-update',
            'halo5:teams-update',
            'halo5:medal-update',
            'halo5:rank-update',
            'halo5:maps-update',
            'halo5:csr-update',
            'halo5:enemies-update',
            'halo5:impulses-update',
            'halo5:vehicles-update',
        ];

        foreach ($commands as $command) {
            $exitCode = \Artisan::call($command);

            $this->info('Ran command ('.$command.') with output of: '.$exitCode);

            $randomSleep = mt_rand(1, 8);
            $this->info('Sleeping for '.$randomSleep.' seconds before next command.');
            sleep($randomSleep);
        }

        $caches = [
            'vehicles-metadata',
            'gametypes-metadata',
            'weapons-metadata',
            'maps-metadata',
            'metadata-metadata',
            'impulses-metadata',
            'medals-metadata',
            'playlists-metadata',
        ];

        foreach ($caches as $name) {
            $this->info('Dumping cache for '.$name);
            \Cache::forget($name);
        }
    }
}
