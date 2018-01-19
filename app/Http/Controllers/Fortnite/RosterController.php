<?php

namespace PandaLove\Http\Controllers\Fortnite;

use Illuminate\Http\Request;
use Onyx\Account;
use Onyx\Fortnite\Objects\Stats;
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
        $accounts = Stats::with('user', 'account')
            ->whereHas('user', function ($query) {
                $query->where('isPanda', true);
            })
            ->join('accounts', 'accounts.id', '=', 'fortnite_stats.account_id')
            ->orderBy('accounts.gamertag', 'ASC')
            ->paginate(10);

        return view('fortnite.roster', [
            'members'     => $accounts,
            'description' => 'PandaLove Fortnite Roster page',
            'title'       => 'PandaLove Fortnite Roster',
        ]);
    }
}
