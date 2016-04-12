<?php namespace PandaLove\Http\Controllers\Backstage;

use Illuminate\Contracts\Auth\Guard;
use Onyx\Account;
use Onyx\Halo5\Client as Halo5Client;
use Onyx\Halo5\Objects\Match;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;
use PandaLove\Http\Requests\AddHalo5GamertagRequest;

class Halo5Controller extends Controller {

    public function __construct(Guard $auth)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function getIndex()
    {
        $accounts = Account::with('h5', 'user')
            ->whereHas('h5', function($query)
            {
                $query->where('Xp', '!=', 0);
            })
            ->orderBy('id', 'DESC')
            ->paginate(15);

        $maps = \DB::table('halo5_matches')
            ->leftJoin('halo5_maps', 'halo5_matches.map_id', '=', 'halo5_maps.contentId')
            ->select(\DB::raw('count(*) as total'), 'halo5_maps.name')
            ->groupBy('map_variant')
            ->orderBy('total', 'DESC')
            ->get();

        return view('backstage.halo5.index', [
            'accounts' => $accounts,
            'maps' => $maps,
        ]);
    }

    public function postAddHalo5Gamertag(AddHalo5GamertagRequest $request)
    {
        $client = new Halo5Client();
        $account = $client->getAccountByGamertag($request->request->get('gamertag'));

        $this->dispatch(new UpdateHalo5Account($account));

        return \Redirect::action('Halo5\ProfileController@index', [$account->seo]);
    }
}
