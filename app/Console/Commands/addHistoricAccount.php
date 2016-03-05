<?php

namespace PandaLove\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Account;
use Onyx\Halo5\Objects\Data;
use Onyx\Halo5\Objects\HistoricalStat;
use Onyx\Hangouts\Helpers\Messages;
use Onyx\User;

class addHistoricAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:add-historic {user_id}';

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
        $user_id = $this->argument('user_id');

        try
        {
            $user = User::where('id', intval($user_id))->firstOrFail();

            if (! $user->isPanda)
            {
                $this->error('This user is not Panda');
            }
            else
            {
                if ($user->account->h5 instanceof Data)
                {
                    $count = HistoricalStat::where('account_id', $user->account->id)->count();

                    if ($count == 0)
                    {
                        $dates = HistoricalStat::where('account_id', 1)
                            ->groupBy('date')
                            ->get();

                        /** @var $panda Account */
                        $panda = $user->account;

                        foreach ($dates as $date)
                        {
                            $historic = new HistoricalStat();
                            $historic->account_id = $panda->id;
                            $historic->arena_kd = $panda->h5->kd(false);
                            $historic->arena_kda = $panda->h5->kad(false);
                            $historic->arena_total_games = $panda->h5->totalGames;
                            $historic->warzone_kd = $panda->h5->warzone->kd(false);
                            $historic->warzone_kda = $panda->h5->warzone->kad(false);
                            $historic->warzone_total_games = $panda->h5->warzone->totalGames;
                            $historic->date = $date->date;
                            $historic->save();
                        }
                    }
                    else
                    {
                        $this->error('You are already an account in this.');
                    }
                }
                else
                {
                    $this->error('This user does not even play Halo 5.');
                }
            }
        }
        catch (ModelNotFoundException $ex)
        {
            $this->error('Account not found.');
        }
    }
}
