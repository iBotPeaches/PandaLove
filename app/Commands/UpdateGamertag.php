<?php namespace PandaLove\Commands;

use Onyx\Destiny\Client;
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
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$client = new Client();
		$account = $client->fetchAccountByGamertag($this->type, $this->gamertag);
		$client->fetchAccountData($account);
	}
}
