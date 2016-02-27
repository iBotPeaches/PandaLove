<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Http\Request;
use Onyx\Halo5\Objects\HistoricalStat;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;
use Illuminate\Database\Connection as DB;

class StatsController extends Controller {

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var DB
     */
    private $db;

    public function __construct(Request $request, DB $db)
    {
        parent::__construct();
        $this->request = $request;
        $this->db = $db;
    }

    public function getIndex()
    {
        return view('halo5.historic_stats', [
            'description' => 'PandaLove Halo 5 Historic Stats page',
            'title' => 'PandaLove Halo 5 Historic Stats'
        ]);
    }

    public function getArenaStats()
    {
        /** @var $stats HistoricalStat[] */
        $stats = $this->db
            ->table('halo5_stats_history as H')
            ->join('accounts as A', 'A.id', '=', 'H.account_id')
            ->select('H.account_id', 'H.arena_kd', 'H.arena_kda', 'H.arena_total_games', 'H.date', 'A.gamertag')
            ->orderBy('date', 'ASC')
            ->get();

        $data = ['c3' => ['x' => [$stats[0]->date]]];

        foreach ($stats as $stat)
        {
            if (! in_array($stat->date, $data['c3']['x']))
            {
                $data['c3']['x'][] = $stat->date;
            }
            $data['c3'][$stat->gamertag][] = $stat->arena_kda;
            $data['totalGames'][$stat->gamertag][] = $stat->arena_total_games;
        }
        arsort($data['c3']);

        return json_encode($data, true, JSON_NUMERIC_CHECK);
    }

    public function getWarzoneStats()
    {
        /** @var $stats HistoricalStat[] */
        $stats = $this->db
            ->table('halo5_stats_history as H')
            ->join('accounts as A', 'A.id', '=', 'H.account_id')
            ->select('H.account_id', 'H.warzone_kd', 'H.warzone_kda', 'H.warzone_total_games', 'H.date', 'A.gamertag')
            ->orderBy('date', 'DESC')
            ->get();

        $data = [
            'x' => [$stats[0]->date]
        ];

        foreach ($stats as $stat)
        {
            if (! in_array($stat->date, $data['x']))
            {
                $data['x'][] = $stat->date;
            }

            //$data[$stat->gamertag . '_kd'][] = $stat->warzone_kd;
            $data[$stat->gamertag][] = $stat->warzone_kda;
        }

        arsort($data);

        return json_encode($data, true, JSON_NUMERIC_CHECK);
    }
}
