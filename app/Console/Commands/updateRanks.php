<?php

namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\Rank;

class updateRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:rank-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls down Rank information (should only ever need to be run once)';

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
        $client = new Client();
        $this->info('Getting new Rank data from 343');
        $ranks = $client->getRanks();

        if (is_array($ranks)) {
            $this->info('We found Rank data. Adding/Updating now.');

            foreach ($ranks as $rank) {
                try {
                    $_rank = Rank::where('level', $rank['id'])->firstOrFail();
                    $this->info('Updating Level '.$rank['id']);
                    // nothing to update
                } catch (ModelNotFoundException $ex) {
                    $this->info('Adding Level '.$rank['id']);

                    $r = new Rank();
                    $r->level = $rank['id'];
                    $r->uuid = $rank['contentId'];
                    $r->startXp = $rank['startXp'];
                    $r->save();
                }
            }
        }
    }
}
