<?php namespace Onyx\Destiny\Helpers\Utils;

use Illuminate\Support\Collection;

class Game {

    /**
     * @param $kills
     * @param $deaths
     * @return float
     */
    public static function kd($kills, $deaths)
    {
        if ($deaths == 0)
        {
            return $kills;
        }
        else
        {
            return round($kills / $deaths, 2);
        }
    }

    /**
     * @param $kills
     * @param $assists
     * @param $deaths
     * @return float
     */
    public static function kadr($kills, $assists, $deaths)
    {
        $total = $kills + $assists;

        if ($deaths == 0)
        {
            return $total;
        }
        else
        {
            return round($total / $deaths, 2);
        }
    }

    public static function buildCombinedStats($games)
    {
        // combined numbers (move out of controller in future)
        $combined = [];
        foreach($games as $game)
        {
            foreach($game->players as $player)
            {
                if (! $player->completed) continue;

                if (isset($combined[$player->membershipId]))
                {
                    $combined[$player->membershipId]['kills'] += $player->kills;
                    $combined[$player->membershipId]['deaths'] += $player->deaths;
                    $combined[$player->membershipId]['assists'] += $player->assists;
                    $combined[$player->membershipId]['level'] += $player->level;
                    $combined[$player->membershipId]['count'] += 1;
                }
                else
                {
                    $combined[$player->membershipId] = [
                        'kills' => $player->kills,
                        'deaths' => $player->deaths,
                        'assists' => $player->assists,
                        'level' => $player->level,
                        'count' => 1
                    ];
                }
            }
        }

        foreach($combined as $key => $user)
        {
            $combined[$key]['avgLevel'] = round($user['level'] / $user['count'], 1);
            $combined[$key]['kdr'] = Game::kd($user['kills'], $user['deaths']);
            $combined[$key]['kadr'] = Game::kadr($user['kills'], $user['assists'], $user['deaths']);
        }

        $combined = new Collection($combined);
        $combined->sortByDesc('kdr');

        return $combined;
    }
}