<?php namespace Onyx\Destiny\Helpers\Utils;

use Illuminate\Support\Collection;
use Onyx\Destiny\Helpers\String\Text;

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

                    // find players max level
                    $combined[$player->membershipId]['maxLevel'] = max($combined[$player->membershipId]['maxLevel'], $player->level);
                }
                else
                {
                    $combined[$player->membershipId] = [
                        'kills' => $player->kills,
                        'deaths' => $player->deaths,
                        'assists' => $player->assists,
                        'level' => $player->level,
                        'count' => 1,
                        'maxLevel' => 0,
                        'class' => $player->class,
                        'charId' => $player->characterId
                    ];

                    if (isset($player->account->gamertag))
                    {
                        $extra = [
                            'gamertag' => $player->account->gamertag,
                            'seo' => $player->account->seo,
                            'isPandaLove' => $player->account->isPandaLove()
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

    public static function buildQuickPassageStats($games)
    {
        $combined = [];
        $combined['stats'] = [
            'pandaPts' => 0,
            'opponentPts' => 0,
            'pandaWins' => 0,
            'opponentWins' => 0,
            'totalGames' => 0,
            'blowoutGames' => 0
        ];

        $combined['buffs'] = [
            'favor' => false,
            'mercy' => false,
            'boon' => false,
            'boon-or-favor' => false,
            'quitout' => 0
        ];

        foreach($games as $game)
        {
            $pandaId = $game->pvp->pandaId;
            $opponentId = $game->pvp->opposite($pandaId);

            $combined['stats']['pandaPts'] += $game->pvp->pts($pandaId);
            $combined['stats']['opponentPts'] += $game->pvp->pts($opponentId);
            $combined['stats']['pandaWins'] += (($pandaId == $game->pvp->winnerId) ? 1 : 0);
            $combined['stats']['opponentWins'] += (($opponentId == $game->pvp->winnerId) ? 1 : 0);
            $combined['stats']['totalGames'] += 1;

            // Check if PandaLove blew them out (15 - 0)
            if ($pandaId == $game->pvp->winnerId)
            {
                if ($game->pvp->pts($pandaId) == 15 && $game->pvp->pts($opponentId) == 0)
                {
                    $combined['stats']['blowoutGames'] += 1;
                }
            }

            foreach($game->players as $player)
            {
                $id = $player->account->membershipId;

                if ($player->account->isPandaLove())
                {
                    // check for unbroken
                    if ($player->deaths == 0)
                    {
                        if (isset($combined['stats']['unbroken'][$id]))
                        {
                            $combined['stats']['unbroken'][$id]['count'] += 1;
                        }
                        else
                        {
                            $combined['stats']['unbroken'][$id] = [
                                'gamertag' => $player->account->gamertag,
                                'seo' => $player->account->seo,
                                'count' => 1
                            ];
                        }
                    }
                }
            }
        }

        // Lets check for Boon/Mercy/Favor of Osiris
        if ($combined['stats']['pandaWins'] != $combined['stats']['totalGames'])
        {
            // Our Panda # of wins does not equal total games, therefore a loss was encountered
            $combined['buffs']['mercy'] = true;
        }

        if ($combined['stats']['pandaWins'] == 8)
        {
            // We have 8 wins. This means the group could of either used a Boon (First win = two wins)
            // or a Favor (start with 1 win).
            $combined['buffs']['boon-or-favor'] = true;
        }

        if ($combined['stats']['pandaWins'] == 7)
        {
            // We have 7 wins. That means both the Boon and Favor was used.
            $combined['buffs']['favor'] = true;
            $combined['buffs']['boon'] = true;
        }

        if ($combined['stats']['pandaWins'] < 7)
        {
            $combined['buffs']['quitout'] = (7 - $combined['stats']['pandaWins']);
        }


        return $combined;
    }
}