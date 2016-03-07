<?php namespace PandaLove\Commands;

use Onyx\Destiny\Client as DestinyClient;
use PandaLove\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

class UpdateGamertag extends Command implements SelfHandling {

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
	 * @return \Onyx\Account
	 * @throws \Onyx\Destiny\PlayerNotFoundException
	 */
	public function handle()
	{
		$client = new DestinyClient();

		\DB::transaction(function () use ($client)
		{
			$account = $client->fetchAccountByGamertag($this->type, $this->gamertag);
			$client->fetchAccountData($account);

			return $account;
		});
	}
}
