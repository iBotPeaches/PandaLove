<?php

namespace PandaLove\Http\Controllers\Halo5;

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
        $accounts = Account::with('user', 'h5.playlists.stock', 'h5.playlists', 'h5.warzone')
            ->whereHas('user', function ($query) {
                $query->where('isPanda', true);
            })
            ->whereHas('h5', function ($query) {
                $query->where('totalKills', '!=', 0);
            })
            ->orderBy('gamertag', 'ASC')
            ->paginate(10);

        return view('halo5.roster', [
            'members'     => $accounts,
            'description' => 'PandaLove Halo 5 Roster page',
            'title'       => 'PandaLove Halo 5 Roster',
        ]);
    }
}
