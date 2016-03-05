<?php namespace PandaLove\Http\Controllers\Backstage;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Account;
use Onyx\Destiny\Client as DestinyClient;
use Onyx\Halo5\Client as Halo5Client;
use Onyx\User;
use PandaLove\Commands\UpdateAccount;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;
use PandaLove\Http\Requests\AdminAddDestinyGamertagRequest;
use PandaLove\Http\Requests\AdminAddHalo5GamertagRequest;
use PandaLove\Http\Requests\AddGameRequest;

class IndexController extends Controller {

    public function __construct(Guard $auth)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function getIndex()
    {
        return redirect('/backstage/pandas');
    }

    public function getPandas()
    {
        return view('backstage.pandas', [
            'users' => User::with('account.h5', 'account.destiny')->where('isPanda', true)->orderBy('name')->paginate(15)
        ]);
    }

    public function getSetPanda($account_id = 0)
    {
        try
        {
            $account = Account::with('user')->where('id', intval($account_id))->firstOrFail();

            if ($account->user instanceof User)
            {
                $account->user->isPanda = true;
                $account->user->save();

                return \Redirect::action('Backstage\IndexController@getPandas')
                    ->with('flash_message', [
                        'type' => 'green',
                        'header' => $account->gamertag . ' is now a Panda.',
                        'close' => false
                    ]);
            }
            else
            {
                return \Redirect::action('Backstage\IndexController@getPandas')
                    ->with('flash_message', [
                        'type' => 'danger',
                        'header' => 'This account has not binded a Google Account to themselves.',
                        'close' => false,
                        'body' => 'So this is not possible.'
                    ]);
            }
        }
        catch (ModelNotFoundException $e)
        {
            return \Redirect::action('Backstage\IndexController@getPandas')
                ->with('flash_message', [
                    'type' => 'danger',
                    'header' => 'This person was not found.',
                    'close' => false,
                    'body' => 'You tried to set a Panda that does not exist.'
                ]);
        }
    }
}
