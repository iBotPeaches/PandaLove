<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Http\Request;
use Onyx\Account;
use Onyx\Halo5\Client;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;

class RosterController extends Controller {

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
        $client = new Client();
        $client->addMatchEvents('f5384351-0240-4e5c-8f6a-3834c095a466');

        $accounts = Account::with('user', 'h5.playlists.stock', 'h5.playlists', 'h5.warzone')
            ->whereHas('user', function($query)
            {
                $query->where('isPanda', true);
            })
            ->whereHas('h5', function($query)
            {
                $query->where('totalKills', '!=', 0);
            })
            ->orderBy('gamertag', 'ASC')
            ->paginate(10);

        return view('halo5.roster', [
            'members' => $accounts,
            'description' => 'PandaLove Halo 5 Roster page',
            'title' => 'PandaLove Halo 5 Roster'
        ]);
    }
}
