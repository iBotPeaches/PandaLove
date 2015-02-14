<?php namespace PandaLove\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Onyx\Destiny\Client;
use PandaLove\Commands\UpdateAccount;
use PandaLove\Commands\UpdateGamertag;
use PandaLove\Http\Requests;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests\AdminAddGamertagRequest;
use PandaLove\Http\Requests\AddGameRequest;

class AdminController extends Controller
{

    public function __construct(Guard $auth)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function postAddGamertag(AdminAddGamertagRequest $request)
    {
        $client = new Client();
        $account = $client->fetchAccountByGamertag(1, $request->request->get('gamertag'));

        $this->dispatch(new UpdateAccount($account));

        return \Redirect::action('ProfileController@index', [$account->seo]);
    }

    public function postAddGame(AddGameRequest $request)
    {
        $client = new Client();

        $client->updateTypeOfGame($request->request->get('instanceId'), $request->request->get('type'), $request->request->get('raidTuesday'));

        return \Redirect::action('UserCpController@getIndex')
            ->with('flash_message', [
                'type' => 'success',
                'header' => 'Game Added!'
            ]);
    }
}
