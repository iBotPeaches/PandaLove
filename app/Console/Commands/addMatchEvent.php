<?php

namespace PandaLove\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Account;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\Data;
use Onyx\Halo5\Objects\HistoricalStat;
use Onyx\Hangouts\Helpers\Messages;
use Onyx\User;

class addMatchEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:add-match-event {matchId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls the massive events column and stores properly.';

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
        $matchId = $this->argument('matchId');

        \DB::beginTransaction();

        try
        {
            $client = new Client();
            $client->addMatchEvents($matchId);

            \DB::commit();
        }
        catch (\Exception $ex)
        {
            \DB::rollBack();
        }
    }
}
