<?php namespace PandaLove\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use PandaLove\Http\Requests;
use Onyx\Halo5\Client as Halo5Client;
use Onyx\Destiny\Client as DestinyClient;
use PandaLove\Commands\UpdateDestinyAccount;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Requests\AddHalo5GamertagRequest;
use PandaLove\Http\Requests\AddDestinyGamertagRequest;

class AccountController extends Controller {

    public function __construct(Guard $auth)
    {
        parent::__construct();
    }

    //---------------------------------------------------------------------------------
    // GET
    //---------------------------------------------------------------------------------

    public function getIndex()
    {
        return view('account.index', [
            'title' => 'PandaLove Account Adder'
        ]);
    }

    //---------------------------------------------------------------------------------
    // POST
    //---------------------------------------------------------------------------------

    public function postAddHalo5Gamertag(AddHalo5GamertagRequest $request)
    {
        $client = new Halo5Client();
        $account = $client->getAccountByGamertag($request->request->get('gamertag'));

        $this->dispatch(new UpdateHalo5Account($account));

        return \Redirect::action('Halo5\ProfileController@index', [$account->seo]);
    }

    public function postAddDestinyGamertag(AddDestinyGamertagRequest $request)
    {
        $client = new DestinyClient();
        $account = $client->fetchAccountByGamertag(1, $request->request->get('gamertag'));

        $this->dispatch(new UpdateDestinyAccount($account));

        return \Redirect::action('Destiny\ProfileController@index', [$account->seo]);
    }
}
