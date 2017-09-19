<?php

namespace PandaLove\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Onyx\Account;
use Onyx\Destiny\Client as DestinyClient;
use Onyx\Halo5\Client as Halo5Client;
use Onyx\Halo5\H5PlayerNotFoundException;
use Onyx\Halo5\Objects\Data as Halo5Data;
use Onyx\Overwatch\Client as OverwatchClient;
use PandaLove\Commands\UpdateDestinyAccount;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Requests\AddDestiny2GamertagRequest;
use PandaLove\Http\Requests\AddDestinyGamertagRequest;
use PandaLove\Http\Requests\AddHalo5GamertagRequest;
use PandaLove\Http\Requests\AddOverwatchRequest;

class AccountController extends Controller
{
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
            'title'   => 'PandaLove Account Adder',
        ]);
    }

    //---------------------------------------------------------------------------------
    // POST
    //---------------------------------------------------------------------------------

    public function postAddHalo5Gamertag(AddHalo5GamertagRequest $request)
    {
        try {
            $client = new Halo5Client();
            $account = $client->getAccountByGamertag($request->request->get('gamertag'));

            if (!$account->h5 instanceof Halo5Data) {
                $this->dispatch(new UpdateHalo5Account($account));
            } else {
                $this->dispatch(new UpdateHalo5Account($account));
            }

            return \Redirect::action('Halo5\ProfileController@index', [$account->seo]);
        } catch (H5PlayerNotFoundException $ex) {
            return redirect('/account')
                ->with('flash_message', [
                'close'  => 'true',
                'type'   => 'yellow',
                'header' => 'Uh oh',
                'body'   => 'We could not find this gamertag.',
            ]);
        }
    }

    public function postAddOverwatchGamertag(AddOverwatchRequest $request)
    {
        try {
            $client = new OverwatchClient();

            $gamertag = $request->request->get('gamertag');
            $platform = $request->request->get('platform');

            /** @var Account $account */
            $account = $client->getAccountByTag($gamertag, $platform);

            return \Redirect::action('Overwatch\ProfileController@index', [$account->seo, $account->accountType]);
        } catch (\Exception $ex) {
            return redirect('/account', [
                'close'  => true,
                'type'   => 'yellow',
                'header' => 'Uh oh',
                'body'   => 'We could not find this name on either Xbox/PS/PC',
            ]);
        }
    }

    public function postAddDestiny2Gamertag(AddDestiny2GamertagRequest $request)
    {
        dd($request);
    }

    public function postAddDestinyGamertag(AddDestinyGamertagRequest $request)
    {
        try {
            $client = new DestinyClient();

            $gamertag = $request->request->get('gamertag');
            $platform = $request->request->get('platform');

            /* @var $accounts Account[] */
            if ($platform != null) {
                $account = $client->fetchAccountByGamertag($platform, $gamertag);
            } else {
                $accounts = $client->searchAccountByName($gamertag);

                if (count($accounts) > 1) {
                    return redirect('/destiny/platform-switch/'.$accounts[0]->gamertag);
                } else {
                    $account = $accounts[0];
                }
            }

            if ($account->destiny->grimoire != 0) {
                return \Redirect::action('Destiny\ProfileController@index', [$account->accountType, $account->seo]);
            }

            $this->dispatch(new UpdateDestinyAccount($account));

            return \Redirect::action('Destiny\ProfileController@index', [$account->accountType, $account->seo]);
        } catch (\Exception $ex) {
            return redirect('/account')
                ->with('flash_message', [
                    'close'  => 'true',
                    'type'   => 'yellow',
                    'header' => 'Uh oh',
                    'body'   => 'We could not find this name on either PSN or Xbox.',
                ]);
        }
    }
}
