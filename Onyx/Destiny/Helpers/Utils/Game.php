<?php namespace Onyx\Destiny\Helpers\Utils;

use Illuminate\Support\Collection;
use Onyx\Destiny\Helpers\String\Text;

class Game {

    /**
     * Used for converting team value to string
     *
     * @var array
     */
    private $team_val_to_str = array(
        16 => 'Alpha',
        17 => 'Bravo',
    );

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
        $gameCount = 0;
        $timeCount = 0;

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

                    if (isset($player->account->gamertag))
                    {
                        $extra = [
                            'gamertag' => $player->account->gamertag,
                            'seo' => $player->account->seo
                        ];

                        $combined[$player->membershipId] = array_merge($combined[$player->membershipId], ['player' => $extra]);
                    }
                }
            }

            $game->players->each(function($player)
            {
                $player->kd = $player->kdr();
            })->sortByDesc('kd');

            $gameCount++;
            $timeCount += $game->getRawSeconds();
        }

        foreach($combined as $key => $user)
        {
            $combined[$key]['avgLevel'] = round($user['level'] / $user['count'], 1);
            $combined[$key]['kdr'] = Game::kd($user['kills'], $user['deaths']);
            $combined[$key]['kadr'] = Game::kadr($user['kills'], $user['assists'], $user['deaths']);
        }

        $combined = new Collection($combined);
        $combined->sortByDesc('kdr');

        return [
            'players' => $combined,
            'stats' => [
                'games' => $gameCount,
                'combinedGameTime' => Text::timeDuration($timeCount)
            ]
        ];
    }

    /**
     * Converts team value into string
     *
     * @param $value
     * @return string
     */
    public static function team($value)
    {
        if(isset($team_val_to_str[$value]))
        {
            return $team_val_to_str[$value];
        }
    }
}