<?php namespace PandaLove\Http\Controllers\Backstage;

use Illuminate\Contracts\Auth\Guard;
use Onyx\Halo5\Client as Halo5Client;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;
use PandaLove\Http\Requests\AdminAddHalo5GamertagRequest;

class Halo5Controller extends Controller {

    public function __construct(Guard $auth)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function getIndex()
    {
        return view('backstage.index');
    }

    public function postAddHalo5Gamertag(AdminAddHalo5GamertagRequest $request)
    {
        $client = new Halo5Client();
        $account = $client->getAccountByGamertag($request->request->get('gamertag'));

        $this->dispatch(new UpdateHalo5Account($account));

        return \Redirect::action('Halo5\ProfileController@index', [$account->seo]);
    }
}
