<?php namespace PandaLove\Http\Controllers;

use Onyx\Account;
use PandaLove\Http\Requests;
use PandaLove\Http\Controllers\Controller;

use Illuminate\Http\Request;

class RosterController extends Controller {

	public function getIndex()
    {
        $members = Account::with('characters')
            ->where('clanName', 'Panda Love')
            ->orderBy('gamertag', 'ASC')
            ->paginate(7);

        return view('roster', ['members' => $members]);
    }
}
