<?php namespace PandaLove\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Onyx\Destiny\Client;
use PandaLove\Commands\UpdateAccount;
use PandaLove\Http\Requests;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests\AdminAddGamertagRequest;

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
}
