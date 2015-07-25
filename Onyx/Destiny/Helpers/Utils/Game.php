<?php namespace Onyx\Destiny\Helpers\Utils;

use Illuminate\Support\Collection;
use Onyx\Destiny\Helpers\String\Hashes;
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
        $revives = false;

        foreach($games as $game)
        {
            // Check for Trials game, and check if any non Panda are playing
            $pandaId = 0;
            if ($game->isToO())
            {
                $pandaId = $game->pvp->pandaId;
            }

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
                    $combined[$player->membershipId]['revives_given'] += $player->revives_given;
                    $combined[$player->membershipId]['revives_taken'] += $player->revives_taken;

                    if ($player->revives_given != 0)
                    {
                        $revives = true;
                    }

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
                        'charId' => $player->characterId,
                        'revives_given' => 0,
                        'revives_taken' => 0
                    ];

                    if (isset($player->account->gamertag))
                    {
                        $extra = [
                            'gamertag' => $player->account->gamertag,
                            'seo' => $player->account->seo,
                            'isPandaLove' => $player->account->isPandaLove(),
                            'isPandaGuest' => false
                        ];

                        // Check if this player is on PandaTeam, if so mark them as Panda
                        if ($game->isToO())
                        {
                            if (! $extra['isPandaLove'] && $player->team == $pandaId)
                            {
                                $extra['isPandaLove'] = true;
                                $extra['isPandaGuest'] = true;
                            }
                        }

                        $combined[$player->membershipId] = array_merge($combined[$player->membershipId], ['player' => $extra]);
                    }
                }
            }

            $game->players = $game->players->each(function($player)
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
        $combined = $combined->sortByDesc('kdr');

        return [
            'players' => $combined,
            'stats' => [
                'games' => $gameCount,
                'combinedGameTime' => Text::timeDuration($timeCount),
                'revives' => $revives
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
            'blowoutGames' => 0,
            'differentMaps' => false
        ];

        $combined['buffs'] = [
            'favor' => false,
            'mercy' => false,
            'boon' => false,
            'boon-or-favor' => false,
            'quitout' => 0
        ];

        $previous = null;
        $maps = new Collection();

        foreach($games as $game)
        {
            $pandaId = $game->pvp->pandaId;
            $opponentId = $game->pvp->opposite($pandaId);

            if ($maps->has($game->referenceId))
            {
                $count = $maps->get($game->referenceId);
                $maps->forget($game->referenceId);
                $maps->put($game->referenceId, ++$count);
            }
            else
            {
                $maps->put($game->referenceId, 1);
            }

            $combined['stats']['pandaPts'] += $game->pvp->pts($pandaId);
            $combined['stats']['opponentPts'] += $game->pvp->pts($opponentId);
            $combined['stats']['pandaWins'] += (($pandaId == $game->pvp->winnerId) ? 1 : 0);
            $combined['stats']['opponentWins'] += (($opponentId == $game->pvp->winnerId) ? 1 : 0);
            $combined['stats']['totalGames'] += 1;

            // Check if PandaLove blew them out (15 - 0)
            // Update: Trials #2 maxes at 5-0
            // Update: Forget max, just check if enemy got 0pts
            if ($pandaId == $game->pvp->winnerId)
            {
                if ($game->pvp->pts($opponentId) == 0)
                {
                    $combined['stats']['blowoutGames'] += 1;
                }
            }

            if ($previous == null)
            {
                $previous = $game->referenceId;
            }
            else
            {
                if ($previous != $game->referenceId)
                {
                    $combined['stats']['differentMaps'] = true;
                }
            }

            foreach($game->players as $player)
            {
                $id = $player->account->membershipId;

                if ($player->account->isPandaLove() || $player->team == $pandaId)
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

        // are we on different maps? If so lets get the names of them
        if ($combined['stats']['differentMaps'])
        {
            $map_list = '';
            $new_maps = null;

            $maps->each(function($count, $map) use (&$map_list, &$new_maps)
            {
                $map_list .= Hashes::quick($map)['title'] . ", ";
            });

            $combined['stats']['maps'] = rtrim($map_list, ", ");
            $new_maps = $maps->toArray();
            arsort($new_maps);
            $combined['stats']['rMaps'] = $new_maps;
        }

        $bonus = 0;
        if ($combined['stats']['pandaWins'] < 7)
        {
            $combined['buffs']['quitout'] = (7 - $combined['stats']['pandaWins']);
            $bonus += $combined['buffs']['quitout'];
        }

        // Lets check for Boon/Mercy/Favor of Osiris
        if ($combined['stats']['pandaWins'] != $combined['stats']['totalGames'])
        {
            // Our Panda # of wins does not equal total games, therefore a loss was encountered
            $combined['buffs']['mercy'] = true;
        }

        if ($combined['stats']['pandaWins'] == (8 - $bonus))
        {
            // We have 8 wins. This means the group could of either used a Boon (First win = two wins)
            // or a Favor (start with 1 win).
            $combined['buffs']['boon-or-favor'] = true;
        }

        if ($combined['stats']['pandaWins'] == (7 - $bonus))
        {
            // We have 7 wins. That means both the Boon and Favor was used.
            $combined['buffs']['favor'] = true;
            $combined['buffs']['boon'] = true;
        }

        return $combined;
    }

    /**
     * @param $games
     * @param $gameId
     * @return bool
     */
    public static function gameIdExists($games, $gameId)
    {
        foreach($games as $game)
        {
            if ($game->instanceId == $gameId)
            {
                return $gameId;
            }
        }

        return false;
    }

    public static function getMaps($passages)
    {
        if ($passages instanceof Collection)
        {
            $passages->each(function($passage)
            {
                $passage['message'] = self::explodeMap($passage->maps);
            });
        }

        return $passages;
    }

    public static function explodeMap($list)
    {
        $maps = explode(",", $list);

        $different = false;
        $previous = $maps[0];

        if (is_array($maps))
        {
            foreach ($maps as $map)
            {
                if ($previous != $map)
                {
                    $different = true;
                    break;
                }
            }
        }

        // check if we played on different maps
        if ($different)
        {
            return ' a variety of maps';
        }
        else
        {
            return false; // defer loading to view so we can have autoloaded hashes
        }
    }
}