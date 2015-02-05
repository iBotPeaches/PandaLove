<?php namespace PandaLove\Http\Controllers;

use Onyx\Destiny\Client as Client;

class HomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('home');
		//$client = new Client();
		//$account = $client->fetchAccountByGamertag(1, 'iBotPeaches v5');

		//return $client->fetchAccountData($account, 1, $account->membershipId);
	}
}
