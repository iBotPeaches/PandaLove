<?php

namespace PandaLove\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Onyx\Account;
use Onyx\Halo5\Objects\HistoricalStat;
use Onyx\Hangouts\Helpers\Messages;

class updateHistoricStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:historic-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls daily kd information and inserts record into halo5_stats_history table';

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
        $pandas = Account::with('user', 'h5.warzone')
            ->whereHas('user', function ($query) {
                $query->where('isPanda', true);
            })
            ->whereHas('h5', function ($query) {
                $query->where('totalKills', '!=', 0);
            })
            ->orderBy('gamertag', 'ASC')
            ->get();

        $messenger = new Messages();

        /** @var $pandas \Onyx\Account[] */
        $insertedDate = new Carbon();
        foreach ($pandas as $panda) {
            $this->info('Writing out Panda '.$panda->gamertag);
            $historic = new HistoricalStat();
            $historic->account_id = $panda->id;
            $historic->arena_kd = $panda->h5->kd(false);
            $historic->arena_kda = $panda->h5->kad(false);
            $historic->arena_total_games = $panda->h5->totalGames;
            $historic->warzone_kd = $panda->h5->warzone->kd(false);
            $historic->warzone_kda = $panda->h5->warzone->kad(false);
            $historic->warzone_total_games = $panda->h5->warzone->totalGames;
            $historic->date = $insertedDate;
            $historic->save();
        }

        //$messenger->sendGroupMessage('Hi pandas. I have updated the rolling KD/KDA chart automatically.');
    }
}
