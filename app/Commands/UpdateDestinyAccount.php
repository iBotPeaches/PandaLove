<?php namespace PandaLove\Commands;

use Onyx\Account;
use Onyx\Destiny\Client;

use Illuminate\Contracts\Bus\SelfHandling;

class UpdateDestinyAccount extends Command implements SelfHandling {

	private $account;

	/**
	 * Create a new command instance.
	 *
	 * @param \Onyx\Account $account
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
		$client->fetchAccountData($this->account);
	}

}
