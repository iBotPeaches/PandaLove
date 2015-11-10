<?php

namespace PandaLove\Commands;

use Onyx\Account;
use Onyx\Halo5\Client;
use PandaLove\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdateHalo5Account extends Command implements SelfHandling
{
    private $account;

    /**
     * Create a new command instance.
     *
     * @return void
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
        $client->updateH5Account($this->account);
    }
}
