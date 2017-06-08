<?php

namespace PandaLove\Http\Controllers\Overwatch;

use Illuminate\Http\Request;
use Onyx\Account;
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
        $accounts = Account::with('user', 'overwatch.characters')
            ->whereHas('user', function ($query) {
                $query->where('isPanda', true);
            })
            ->whereHas('overwatch', function ($query) {
                $query->where('games', '>=', 10);
            })
            ->orderBy('gamertag', 'ASC')
            ->paginate(10);

        return view('overwatch.roster', [
            'members'     => $accounts,
            'description' => 'PandaLove Overwatch Roster page',
            'title'       => 'PandaLove Overwatch Roster',
        ]);
    }
}
