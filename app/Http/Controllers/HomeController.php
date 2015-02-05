<?php namespace PandaLove\Http\Controllers;

use Illuminate\Html\FormFacade;
use Laravel\Socialite\Facades\Socialite as Socialize;
use Onyx\Destiny\Client as Client;

class HomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 */
	public function __construct()
	{

	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		//return view('home');
		$client = new Client();
		$account = $client->fetchAccountByGamertag(1, 'iBotPeaches v5');

		return $client->fetchAccountData($account, 1, $account->membershipId);
	}

	public function getLogin()
	{
		$test = FormFacade::hidden('yee');
		return $this->redirectToProvider();
	}

	private function redirectToProvider()
	{
		return Socialize::with('google')->redirect();
	}
}
