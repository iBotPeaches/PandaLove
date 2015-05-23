<?php namespace PandaLove\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text;
use PandaLove\Http\Requests;
use PandaLove\Http\Requests\OwnershipFormRequest;

class UserCpController extends Controller {

    public function __construct(Guard $auth)
    {
        parent::__construct();
        $this->middleware('auth', ['except' => 'getLogout']);
    }

    public function getIndex()
    {
        return view('usercp.index', [
            'title' => 'PandaLove Control Panel'
        ]);
    }

    public function postGamertagOwnership(OwnershipFormRequest $request)
    {
        $account = Account::where('seo', Text::seoGamertag($request->request->get('gamertag')))->first();

        $this->user->account_id = $account->id;
        $this->user->save();

        return \Redirect::action('UserCpController@getIndex')
            ->with('flash_message', [
                'header' => 'Gamertag Verified!',
                'close' => true,
                'body' => 'You have proved ownership of <strong>' . $account->gamertag . '</strong>.'
            ]);
    }

    public function getLogout()
    {
        \Auth::logout();

        return \Redirect::to('/')
            ->with('flash_message', [
                'type' => 'green',
                'header' => 'See you soon',
                'close' => true,
                'body' => 'Your sign out was successful.'
            ]);
    }
}
