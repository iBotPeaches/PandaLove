<?php namespace PandaLove\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Onyx\Halo5\Objects\Data;
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
        try
        {
            $client = new Halo5Client();
            $account = $client->getAccountByGamertag($request->request->get('gamertag'));

            if (! $account->h5 instanceof Data)
            {
                $this->dispatch(new UpdateHalo5Account($account));
            }
            return \Redirect::action('Halo5\ProfileController@index', [$account->seo]);

        }
        catch (\Exception $ex)
        {
            return redirect('/account');
        }
    }

    public function postAddDestinyGamertag(AddDestinyGamertagRequest $request)
    {
        try
        {
            $client = new DestinyClient();
            $account = $client->fetchAccountByGamertag(1, $request->request->get('gamertag'));

            $this->dispatch(new UpdateDestinyAccount($account));
        }
        catch (\Exception $ex)
        {
            return redirect('/account');
        }

        return \Redirect::action('Destiny\ProfileController@index', [$account->seo]);
    }
}
