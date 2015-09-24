<?php namespace PandaLove\Http\Controllers;

use Onyx\Account;
use Onyx\Destiny\Helpers\String\Hashes;
use PandaLove\Http\Requests;

class RosterController extends Controller {

	public function getIndex()
    {
        $members = Account::with(['characters' => function($query)
            {
                $query->select('id', 'membershipId', 'characterId', 'emblem', 'level', 'class', 'background');
            }])
            ->where('clanName', 'Panda Love')
            ->orderBy('gamertag', 'ASC')
            ->paginate(13);

        // attempt hash cache
        Hashes::cacheAccountsHashes($members);

        return view('roster', [
            'members' => $members,
            'description' => 'PandaLove Roster page',
            'title' => 'PandaLove Roster'
        ]);
    }
}
