<?php namespace PandaLove\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Onyx\Destiny\Client as DestinyClient;
use Onyx\Halo5\Client as Halo5Client;
use PandaLove\Commands\UpdateAccount;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Requests;
use PandaLove\Http\Requests\AdminAddDestinyGamertagRequest;
use PandaLove\Http\Requests\AdminAddHalo5GamertagRequest;
use PandaLove\Http\Requests\AddGameRequest;

class AdminController extends Controller {

    public function __construct(Guard $auth)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function postAddDestinyGamertag(AdminAddDestinyGamertagRequest $request)
    {
        $client = new DestinyClient();
        $account = $client->fetchAccountByGamertag(1, $request->request->get('gamertag'));

        $this->dispatch(new UpdateAccount($account));

        return \Redirect::action('Destiny\ProfileController@index', [$account->seo]);
    }

    public function postAddHalo5Gamertag(AdminAddHalo5GamertagRequest $request)
    {
        $client = new Halo5Client();
        $account = $client->getAccountByGamertag($request->request->get('gamertag'));

        $this->dispatch(new UpdateHalo5Account($account));

        return \Redirect::action('Halo5\ProfileController@index', [$account->seo]);
    }

    public function postAddDestinyGame(AddGameRequest $request)
    {
        $client = new DestinyClient();

        $client->updateTypeOfGame($request->request->get('instanceId'), $request->request->get('type'), $request->request->get('raidTuesday'));

        return \Redirect::action('UserCpController@getIndex')
            ->with('flash_message', [
                'type' => 'success',
                'header' => 'Game Added!'
            ]);
    }
}
