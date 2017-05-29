<?php

namespace PandaLove\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Onyx\Destiny\Client as DestinyClient;

class UpdateGamertag extends Command implements SelfHandling
{
    public $gamertag;
    public $type;

    /**
     * Create a new command instance.
     *
     * @param $gamertag
     * @param $type
     */
    public function __construct($gamertag, $type)
    {
        $this->gamertag = $gamertag;
        $this->type = $type;
    }

    /**
     * @throws \Onyx\Destiny\PlayerNotFoundException
     *
     * @return \Onyx\Account
     */
    public function handle()
    {
        $client = new DestinyClient();

        \DB::transaction(function () use ($client) {
            $account = $client->fetchAccountByGamertag($this->type, $this->gamertag);
            $client->fetchAccountData($account);

            return $account;
        });
    }
}
