<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Http\Request;
use Onyx\Account;
use Onyx\Halo5\Objects\HistoricalStat;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;

class StatsController extends Controller {

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
        $stats = HistoricalStat::with('account')
            ->orderBy('date', 'DESC')
            ->get();

        /** @var $stat \Onyx\Halo5\Objects\HistoricalStat */
        $formatted_data = [];
        foreach ($stats as $stat)
        {
            $formatted_data[] = [
                'type' => 'line',
                'name' => $stat->account->gamertag
            ];
        }

        return view('halo5.historic_stats', [
            'stats' => $stats,
            'description' => 'PandaLove Halo 5 Historic Stats page',
            'title' => 'PandaLove Halo 5 Historic Stats'
        ]);
    }
}
