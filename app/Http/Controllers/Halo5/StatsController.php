<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Http\Request;
use Illuminate\View\Factory;
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

    /**
     * @var Factory
     */
    private $view;

    public function __construct(Request $request, DB $db, Factory $view)
    {
        parent::__construct();
        $this->request = $request;
        $this->db = $db;
        $this->view = $view;
    }

    public function getIndex()
    {
        $graphs = [
            [
                'title' => 'Arena KD',
                'slug' => 'arena_kd',
            ],
            [
                'title' => 'Arena KDA',
                'slug' => 'arena_kda'
            ],
            [
                'title' => 'Warzone KD',
                'slug' => 'warzone_kd'
            ],
            [
                'title' => 'Warzone KDA',
                'slug' => 'warzone_kda'
            ]
        ];

        return view('halo5.historic_stats', [
            'description' => 'PandaLove Halo 5 Historic Stats page',
            'title' => 'PandaLove Halo 5 Historic Stats',
            'graphs' => $graphs
        ]);
    }

    public function getIndividualGraph($type)
    {
        $allowed = ["arena_kd", "arena_kda", "warzone_kd", "warzone_kda"];

        if (in_array($type, $allowed))
        {
            switch ($type)
            {
                case "arena_kd":
                    return $this->view->make('includes.halo5.stats._graph', [
                        'data' => [
                            'type' => $type,
                            'url' => action('Halo5\StatsController@getArenaKDStats'),
                            'selector' => '#' . $type,
                            'y_axis' => 'KD Ratio'
                        ]
                    ]);

                case "arena_kda":
                    return $this->view->make('includes.halo5.stats._graph', [
                        'data' => [
                            'type' => $type,
                            'url' => action('Halo5\StatsController@getArenaKDAStats'),
                            'selector' => '#' . $type,
                            'y_axis' => 'KDA Ratio'
                        ]
                    ]);

                case "warzone_kd":
                    return $this->view->make('includes.halo5.stats._graph', [
                        'data' => [
                            'type' => $type,
                            'url' => action('Halo5\StatsController@getWarzoneKDStats'),
                            'selector' => '#' . $type,
                            'y_axis' => 'KD Ratio'
                        ]
                    ]);

                case "warzone_kda":
                    return $this->view->make('includes.halo5.stats._graph', [
                        'data' => [
                            'type' => $type,
                            'url' => action('Halo5\StatsController@getWarzoneKDAStats'),
                            'selector' => '#' . $type,
                            'y_axis' => 'KDA Ratio'
                        ]
                    ]);
            }
        }
        else
        {
            return $this->view->make('includes.halo5.stats._overview');
        }
    }

    public function getArenaKDStats()
    {
        /** @var $stats HistoricalStat[] */
        $stats = $this->db
            ->table('halo5_stats_history as H')
            ->join('accounts as A', 'A.id', '=', 'H.account_id')
            ->select('H.account_id', 'H.arena_kd', 'H.arena_total_games', 'H.date', 'A.gamertag')
            ->orderBy('date', 'ASC')
            ->get();

        $data = ['c3' => ['x' => [$stats[0]->date]]];

        foreach ($stats as $stat)
        {
            if (! in_array($stat->date, $data['c3']['x']))
            {
                $data['c3']['x'][] = $stat->date;
            }
            $data['c3'][$stat->gamertag][] = $stat->arena_kd;
            $data['totalGames'][$stat->gamertag][] = $stat->arena_total_games;
        }
        arsort($data['c3']);

        return json_encode($data, true, JSON_NUMERIC_CHECK);
    }

    public function getArenaKDAStats()
    {
        /** @var $stats HistoricalStat[] */
        $stats = $this->db
            ->table('halo5_stats_history as H')
            ->join('accounts as A', 'A.id', '=', 'H.account_id')
            ->select('H.account_id', 'H.arena_kda', 'H.arena_total_games', 'H.date', 'A.gamertag')
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

    public function getWarzoneKDStats()
    {
        /** @var $stats HistoricalStat[] */
        $stats = $this->db
            ->table('halo5_stats_history as H')
            ->join('accounts as A', 'A.id', '=', 'H.account_id')
            ->select('H.account_id', 'H.warzone_kd', 'H.warzone_total_games', 'H.date', 'A.gamertag')
            ->orderBy('date', 'DESC')
            ->get();

        $data = ['c3' => ['x' => [$stats[0]->date]]];

        foreach ($stats as $stat)
        {
            if (! in_array($stat->date, $data['c3']['x']))
            {
                $data['c3']['x'][] = $stat->date;
            }

            $data['c3'][$stat->gamertag][] = $stat->warzone_kd;
            $data['totalGames'][$stat->gamertag][] = $stat->warzone_total_games;
        }
        arsort($data['c3']);

        return json_encode($data, true, JSON_NUMERIC_CHECK);
    }

    public function getWarzoneKDAStats()
    {
        /** @var $stats HistoricalStat[] */
        $stats = $this->db
            ->table('halo5_stats_history as H')
            ->join('accounts as A', 'A.id', '=', 'H.account_id')
            ->select('H.account_id', 'H.warzone_kda', 'H.warzone_total_games', 'H.date', 'A.gamertag')
            ->orderBy('date', 'DESC')
            ->get();

        $data = ['c3' => ['x' => [$stats[0]->date]]];

        foreach ($stats as $stat)
        {
            if (! in_array($stat->date, $data['c3']['x']))
            {
                $data['c3']['x'][] = $stat->date;
            }

            $data['c3'][$stat->gamertag][] = $stat->warzone_kda;
            $data['totalGames'][$stat->gamertag][] = $stat->warzone_total_games;
        }
        arsort($data['c3']);

        return json_encode($data, true, JSON_NUMERIC_CHECK);
    }
}
