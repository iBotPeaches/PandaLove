<?php namespace PandaLove\Http\Controllers\Destiny;

use Illuminate\Http\Request;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Hashes;
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
        $accounts = Account::with('destiny.characters')
            ->whereHas('destiny', function($query)
            {
                $query->where('clanName', 'Panda Love');
            })
            ->orderBy('gamertag', 'ASC')
            ->paginate(15);

        // attempt hash cache
        Hashes::cacheAccountsHashes($accounts);

        return view('destiny.roster', [
            'members' => $accounts,
            'description' => 'PandaLove Roster page',
            'title' => 'PandaLove Roster'
        ]);
    }
}
