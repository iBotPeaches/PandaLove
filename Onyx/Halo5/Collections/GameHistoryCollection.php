<?php namespace Onyx\Halo5\Collections;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Onyx\Account;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Halo5\Objects\Data;
use Onyx\Halo5\Objects\Gametype;
use Onyx\Halo5\Objects\Map;
use Onyx\Halo5\Objects\PlaylistData;

/**
 * Class SeasonCollection
 * @package Onyx\Halo5\Collections
 * @property array $items
 */
class GameHistoryCollection extends Collection
{
    public function __construct(Account $account, $matches)
    {
        $maps = Map::all();
        $gametypes = Gametype::all();

        foreach ($matches as $match)
        {
            $match['GameType'] = $gametypes->where('uuid', $match['GameBaseVariantId'])->first();
            $match['Map'] = $maps->where('uuid', $match['MapId'])->first();

            $game = [
                'gametype' => $match['GameType'],
                'map' => $match['Map'],
                'player' => new Data($match['Players'][0]),
                'date' => new Carbon($match['MatchCompletedDate']['ISO8601Date']),
                'duration' => DateHelper::returnSeconds($match['MatchDuration']),
                'win' => $match['Players'][0]['Result']
            ];

            // fix some cases
            $game['player']['totalDeaths'] = $game['player']['TotalDeaths'];
            $game['player']['totalKills'] = $game['player']['TotalKills'];
            $game['player']['totalAssists'] = $game['player']['TotalAssists'];

            $this->items[$match['Id']['MatchId']] = $game;
        }
    }
}