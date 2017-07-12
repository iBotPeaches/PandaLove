<?php

namespace PandaLove\Console\Commands\Overwatch;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Onyx\Account;
use Onyx\Overwatch\Objects\Stats;
use PandaLove\Commands\UpdateOverwatchAccount;

class updatePandas extends Command
{
    use DispatchesCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pandas:overwatch-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all Pandas (Overwatch) present';

    /**
     * @var int
     */
    public $inactiveCounter = 10;

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
        $pandas = Account::with('user', 'overwatch.characters')
            ->whereHas('user', function ($query) {
                $query->where('isPanda', true);
            })
            ->whereHas('overwatch', function ($query) {
                $query->where('games', '>=', 10);
            })
            ->orderBy('gamertag', 'ASC')
            ->paginate(10);

        /** @var $pandas Account[] */
        foreach ($pandas as $panda) {
            $this->info('Processing '.$panda->gamertag);

            // check for 10 inactive checks
            if ($panda->overwatch->inactive_counter >= $this->inactiveCounter) {
                $this->info('This account has not had new data in awhile.');
            } else {
                $gamesPlayed = $panda->overwatch->games_played;

                $this->dispatch(new UpdateOverwatchAccount($panda));
                sleep(rand(5, 9));

                /** @var Stats $ow */
                $ow = Stats::where('account_id', $panda->id)->where('season', $panda->overwatch->season)->first();

                $this->info('Games Played went from '.number_format($gamesPlayed).' to '.number_format($ow->games_played));
            }
        }
    }
}
