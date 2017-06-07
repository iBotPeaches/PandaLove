<?php

namespace PandaLove\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Onyx\Account;
use Onyx\Overwatch\Client;

/**
 * Class UpdateOverwatchAccount
 * @package PandaLove\Commands
 */
class UpdateOverwatchAccount extends Command implements SelfHandling
{
    private $account;

    /**
     * Create a new command instance
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();

        \DB::transaction(function () use ($client) {
            $client->updateAccount($this->account);
        });
    }
}
