<?php namespace PandaLove\Http\Controllers\Backstage;

use Illuminate\Contracts\Auth\Guard;
use Onyx\Account;
use Onyx\Destiny\Client as DestinyClient;
use PandaLove\Commands\UpdateAccount;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;
use PandaLove\Http\Requests\AdminAddDestinyGamertagRequest;
use PandaLove\Http\Requests\AddGameRequest;

class DestinyController extends Controller {

    public function __construct(Guard $auth)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function getIndex()
    {
        $accounts = Account::with('destiny', 'user')
            ->whereHas('destiny', function($query)
            {
                $query->where('grimoire', '!=', 0);
            })
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('backstage.destiny.index', [
            'accounts' => $accounts
        ]);
    }

    public function postAddDestinyGamertag(AdminAddDestinyGamertagRequest $request)
    {
        $client = new DestinyClient();
        $account = $client->fetchAccountByGamertag(1, $request->request->get('gamertag'));

        $this->dispatch(new UpdateAccount($account));

        return \Redirect::action('Destiny\ProfileController@index', [$account->seo]);
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
