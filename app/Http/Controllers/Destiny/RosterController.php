<?php

namespace PandaLove\Http\Controllers\Destiny;

use Illuminate\Http\Request;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Hashes;
use PandaLove\Http\Controllers\Controller;

class RosterController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function getIndex()
    {
        $accounts = Account::with('user', 'destiny.characters')
            ->whereHas('user', function ($query) {
                $query->where('isPanda', true);
            })
            ->whereHas('destiny', function ($query) {
                $query->where('grimoire', '!=', 0);
            })
            ->orderBy('gamertag', 'ASC')
            ->paginate(15);

        // attempt hash cache
        Hashes::cacheAccountsHashes($accounts);

        return view('destiny.roster', [
            'members'     => $accounts,
            'description' => 'PandaLove Destiny Roster page',
            'title'       => 'PandaLove Destiny Roster',
        ]);
    }
}
