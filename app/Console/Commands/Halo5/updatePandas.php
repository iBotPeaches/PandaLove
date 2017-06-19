<?php

namespace PandaLove\Console\Commands\Halo5;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Onyx\Account;
use Onyx\Halo5\Objects\Data;
use PandaLove\Commands\UpdateHalo5Account;

class updatePandas extends Command
{
    use DispatchesCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pandas:halo5-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all Pandas (Halo 5) present';

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
        $pandas = Account::with('h5.warzone', 'user')
            ->whereHas('user', function ($query) {
                $query->where('isPanda', true);
            })
            ->whereHas('h5', function ($query) {
                $query
                    ->where('inactiveCounter', '<=', $this->inactiveCounter)
                    ->where('totalKills', '!=', 0);
            })
            ->orderBy('gamertag', 'ASC')
            ->get();

        /** @var $pandas Account[] */
        foreach ($pandas as $panda) {
            $this->info('Processing '.$panda->gamertag);

            // check for 10 inactive checks
            if ($panda->h5->inactiveCounter >= $this->inactiveCounter) {
                $this->info('This account has not had new data in awhile.');
            } else {
                $oldXp = $panda->h5->Xp;

                $this->dispatch(new UpdateHalo5Account($panda));
                sleep(9);

                $h5 = Data::where('account_id', $panda->id)->first();

                $this->info('Stats Updated from '.number_format($oldXp).' to '.number_format($h5->Xp));
            }
        }
    }
}
