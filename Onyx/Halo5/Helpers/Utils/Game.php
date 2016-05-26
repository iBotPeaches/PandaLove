<?php namespace Onyx\Halo5\Helpers\Utils;

use Onyx\Halo5\Enums\EventName;
use Onyx\Halo5\Enums\MetadataType;
use Onyx\Halo5\Enums\VictimAgent;
use Onyx\Halo5\Objects\Match;
use Onyx\Halo5\Objects\MatchEvent;
use Onyx\Halo5\Objects\MatchPlayer;
use Onyx\Laravel\Helpers\Text;

class Game {

    /**
     * UUID for No scope award -- Snapshot medal
     */
    const MEDAL_NOSCOPE_UUID = '1986137636';

    /**
     * UUID for Sniper award -- Sniper Kill medal
     */
    const MEDAL_SNIPER_UUID = '775545297';

    /**
     * UUID for Sniper award -- Sniper Headshot medal
     */
    const MEDAL_SNIPER_HEAD_UUID = '848240062';

    /**
     * UUID for Energy Sword
     */
    const WEAPON_ENERGY_SWORD = '2650887244';

    /**
     * If you are viewing this. This code is horrible.
     * Literally 3 functions in this file all do the SAME thing.
     * They iterate through match events and pull whats needed.
     *
     * This could be simplified and sexified so much, but during
     * dev you don't know what you are making until its done.
     *
     * I'll rewrite this when not under pressure of API competition.
     * @param Match $match
     * @return array
     */
    public static function buildRoundArray(Match $match)
    {
        $team_map = [];
        $team_label = [];

        $i = 0;
        foreach ($match->players as $player)
        {
            $team_map[$player->account_id] = ($match->isTeamGame) ? $player->team_id : $player->account_id . "_" . $i++;
        }

        if ($match->isTeamGame)
        {
            foreach ($match->teams as $team)
            {
                $team_label[$team->key] = [
                    'name' => $team->team->name,
                    'id' => $team->key,
                    'team' => $team,
                ];

                foreach ($match->playersOnTeam($team->key) as $player)
                {
                    $team_label[$team->key]['players'][$player->account_id] = $player->account;
                }
            }
        }
        else
        {
            foreach ($match->players as $player)
            {
                $team_label[$player->account_id] = [
                    'name' => $player->account->gamertag,
                    'seo' => $player->account->seo,
                    'id' => $player->account_id,
                    'team' => $player->team,
                    'dnf' => $player->dnf,
                ];
            }
        }

        $data = [];
        $roundWinners = [];
        for ($i = 0; $i < $match->hasRounds(); $i++)
        {
            foreach ($team_label as $team)
            {
                $stats = $team['team']->getRoundStats($i);
                if ($stats['Rank'] == 1)
                {
                    $roundWinners[$i] = $team['id'];
                }

                if ($match->isTeamGame)
                {
                    foreach ($match->playersOnTeam($team['team']->key) as $player)
                    {
                        $data[$i][$team['id']][$player->account_id] = [
                            'kills' => 0,
                            'deaths' => 0,
                            'assists' => 0,
                            'score' => 0,
                            'dnf' => $player->dnf,
                            'extras' => [],
                        ];
                    }
                }
                else
                {
                    $data[$i][$team['id']] = [
                        'kills' => 0,
                        'deaths' => 0,
                        'assists' => 0,
                        'score' => $stats === false ? 0 : $stats['Score'],
                        'dnf' => $team['dnf'],
                        'extras' => [],
                    ];
                }
            }
        }

        $currentRound = 0;
        $killsObtained = false;
        $infectedCount = 1;
        $zombies = [];
        $rounds = [];
        $deathCount = 1;

        foreach ($match->events as $event)
        {
            if ($event->event_name == EventName::RoundStart)
            {
                $currentRound = $event->round_index;
            }
            else if ($event->event_name == EventName::RoundEnd)
            {
                if (! $match->isTeamGame)
                {
                    $numPlayers = 0;
                    foreach ($team_label as $team)
                    {
                        if (! $team['dnf'])
                        {
                            $numPlayers++;
                        }
                    }

                    $rounds[$event->round_index] = [
                        'zombiesWin' => ($infectedCount - 1) >= $numPlayers,
                        'humansWin' => ($infectedCount - 1) < $numPlayers,
                    ];
                }

                $killsObtained = false;
                $infectedCount = 1;
                $zombies = [];
                $deathCount = 1;
            }
            else if ($event->event_name == EventName::WeaponPickup)
            {
                if (! $killsObtained && ! $match->isTeamGame)
                {
                    if ($event->killer_weapon_id == self::WEAPON_ENERGY_SWORD)
                    {
                        $data[$currentRound][$event->killer_id]['extras']['alpha'] = true;
                        $zombies[] = $event->killer_id;
                        $infectedCount++;
                    }
                }
            }
            else if ($event->event_name == EventName::Death)
            {
                $killsObtained = true;

                if ($event->killer_id == null || $event->victim_id == null)
                {
                    // An AI killed someone. We aren't counting this.
                    continue;
                }
                else if ($event->killer_id == $event->victim_id)
                {
                    if ($match->isTeamGame)
                    {
                        $data[$currentRound][$team_map[$event->victim_id]]['extras']['deathCount'] = $deathCount++;
                        $data[$currentRound][$team_map[$event->victim_id]][$event->victim_id]['deaths'] += 1;
                        continue;
                    }
                    if (in_array($event->killer_id, $zombies))
                    {
                        continue;
                    }

                    // Someone killed themself. They R ZOMBIE
                    $zombies[] = $event->victim_id;
                    $data[$currentRound][$event->victim_id]['extras']['infected'] = $infectedCount++;
                    $data[$currentRound][$event->victim_id]['deaths'] += 1;
                    continue;
                }

                $team_killer_id = $team_map[$event->killer_id];
                $team_victim_id = $team_map[$event->victim_id];

                if (! $match->isTeamGame)
                {
                    if (in_array($event->killer_id, $zombies) && ! in_array($event->victim_id, $zombies))
                    {
                        $zombies[] = $event->victim_id;
                        $data[$currentRound][$event->victim_id]['extras']['infected'] = $infectedCount++;
                    }

                    $data[$currentRound][$event->killer_id]['kills'] += 1;
                    $data[$currentRound][$event->victim_id]['deaths'] += 1;
                }
                else
                {
                    $data[$currentRound][$team_killer_id][$event->killer_id]['kills'] += 1;
                    $data[$currentRound][$team_victim_id][$event->victim_id]['deaths'] += 1;
                    $data[$currentRound][$team_victim_id][$event->victim_id]['extras']['deathCount'] = $deathCount++;
                }


                if (count($event->assists) > 0)
                {
                    foreach ($event->assists as $assist)
                    {
                        if ($match->isTeamGame)
                        {
                            $data[$currentRound][$team_killer_id][$event->killer_id]['assists'] += 1;
                        }
                        else
                        {
                            $data[$currentRound][$assist->account_id]['assists'] += 1;
                        }
                    }
                }
            }
            else
            {
                continue;
            }
        }

        foreach ($data as $roundId => &$players)
        {
            foreach ($players as &$player)
            {
                if ($match->isTeamGame)
                {
                    foreach ($player as &$_player)
                    {
                        $_player['kd'] = $_player['deaths'] == 0 ? $_player['kills'] : ($_player['kills'] / $_player['deaths']);
                        $_player['kda'] = $_player['deaths'] == 0 ? ($_player['kills'] + $_player['assists']) : ($_player['kills'] + $_player['assists']) / $_player['deaths'];
                    }

                    uasort($player, function($a, $b) use ($match)
                    {
                        return $b['kd'] - $a['kd'];
                    });
                }
                else
                {
                    $player['kd'] = $player['deaths'] == 0 ? $player['kills'] : ($player['kills'] / $player['deaths']);
                    $player['kda'] = $player['deaths'] == 0 ? ($player['kills'] + $player['assists']) : ($player['kills'] + $player['assists']) / $player['deaths'];
                }
            }

            if (! $match->isTeamGame)
            {
                uasort($players, function($a, $b) use ($match)
                {
                    if ($match->isTeamGame)
                    {
                        return $b['kd'] - $a['kd'];
                    }
                    else
                    {
                        return $b['score'] - $a['score'];
                    }
                });
            }
        }

        return [
            'data' => $data,
            'team' => $team_label,
            'roundWinners' => $roundWinners,
            'roundCount' => $match->hasRounds(),
            'rounds' => $rounds,
        ];
    }

    /**
     * @param Match $match
     * @return string
     */
    public static function buildKillChartArray(Match $match)
    {
        $team_map = [];
        $kill_time = [];
        $kill_feed = [];
        $team_label = [];

        $i = 0;
        foreach ($match->players as &$player)
        {
            if ($match->gametype->isWarzoneFirefight())
            {
                $team_map[$player->account_id] = $match->id . "_" . $i++;
                $player->team_id = $team_map[$player->account_id];
            }
            else
            {
                $team_map[$player->account_id] =  $player->team_id;
            }
        }

        if ($match->isTeamGame && ! $match->gametype->isWarzoneFirefight())
        {
            // Set all teams to 0 kills at 0 seconds
            foreach ($match->teams as $team)
            {
                $kill_time[0][$team->key] = 0;
                $team_label[$team->key] = [
                    'name' => $team->team->name,
                    'color' => $team->team->color,
                    'id' => $team->key,
                ];
            }
        }
        else
        {
            $colors = ['E61919', 'E6A119', 'E5E619', '9CB814', '4D8A0F', '14B84B',
            '19E6C4', '149CB8', '1F36AD', '4E1FAD', '9D26D9', 'D926D9', 'E6193C',
            'E8E3E3', '38302E', '33293D', 'F6CCFF'];

            $i = 0;
            foreach ($match->players as $player)
            {
                $kill_time[0][$player->team_id] = 0;
                $team_label[$player->team_id] = [
                    'name' => $player->account->gamertag,
                    'color' => "#" . $colors[$i++],
                    'id' => $player->team_id,
                ];
            }
        }

        $previousSecond = 0;
        $kill_feed[][] = null;
        foreach ($match->kill_events as $event)
        {
            if ($event->killer_id == null || $event->victim_id == null)
            {
                if ($event->victim_type == VictimAgent::AI && $match->gametype->isWarzoneFirefight() && $event->killer_id != null)
                {
                    // do nothing
                }
                else
                {
                    continue; // no AI killed
                }
            }
            
            /** @var integer $second */
            $second = $event->getOriginal('seconds_since_start');
            $team_id = $team_map[$event->killer_id];

            if ($match->isTeamGame)
            {
                $victim_team_id = isset($team_map[$event->victim_id]) ? $team_map[$event->victim_id] : null;
            }

            if ($event->killer_id === $event->victim_id || (isset($victim_team_id) && $victim_team_id == $team_id))
            {
                $kill_time[$second][$team_id] = $kill_time[$previousSecond][$team_id] - 1; // Suicide or Team Kill
            }
            else
            {
                $kill_time[$second][$team_id] = $kill_time[$previousSecond][$team_id] + 1;
            }
            $kill_feed[$kill_time[$second][$team_id]][$team_id] = $event->getKilledString();

            if ($match->isTeamGame)
            {
                foreach ($match->teams as $team)
                {
                    if (! isset($kill_time[$second][$team->key]))
                    {
                        $kill_time[$second][$team->key] = $kill_time[$previousSecond][$team->key];
                    }
                }
            }
            else
            {
                foreach ($match->players as $player)
                {
                    if (! isset($kill_time[$second][$player->team_id]))
                    {
                        $kill_time[$second][$player->team_id] = $kill_time[$previousSecond][$player->team_id];
                    }
                }
            }

            $previousSecond = $second;
        }

        $label = [];
        $team_data = [];
        // Now lets build the format that the JSON expects
        foreach ($kill_time as $seconds => $teams)
        {
            $label[] = Text::timeDuration($seconds);
            foreach ($teams as $key => $kills)
            {
                $team_data[$key][] = $kills;
            }
        }
        
        $teams = [];
        foreach ($team_label as $key => $data)
        {
            $teams[] = [
                'label' => $data['name'],
                'borderColor' => $data['color'],
                'backgroundColor' => "rgba(" . Color::hex2rgb($data['color']) . ", 0.1)",
                'data' => $team_data[$key],
                'team_id' => $data['id'],
                'fill' => $match->isTeamGame,
            ];
        }

        $json = [
            'labels' => $label,
            'datasets' => $teams,
            'killfeed' => $kill_feed,
        ];

        return json_encode($json);
    }

    /**
     * @param Match $match
     * @return array
     */
    public static function buildCombinedMatchEvents(Match $match)
    {
        $cacheKey = $match->id . "_events";

        if (\Cache::has($cacheKey))
        {
            $cache = \Cache::get($cacheKey);

            if (is_array($cache) && count($cache) > 0)
            {
                return $cache;
            }
            else
            {
                \Cache::forget($cacheKey);
                return self::buildCombinedMatchEvents($match);
            }
        }

        $combined = [];
        $skipNextEvent = false;
        $secondToSkip = -1;

        foreach ($match->events as $event)
        {
            /** @var $second integer */
            $second = $event->getOriginal('seconds_since_start');
            if ($second != $secondToSkip && $secondToSkip != -1)
            {
                $skipNextEvent = false;
                $secondToSkip = 0;
            }
            
            if ($skipNextEvent)
            {
                $secondToSkip = $second;
                continue;
            }

            $id = $event->killer_id == null ? 0 : $event->killer_id;

            if ($event->event_name == EventName::RoundStart)
            {
                $skipNextEvent = true;
            }

            $combined[$second][$id][] = $event;
            $combined[$second]['stats'] = [
                'time' => $event->seconds_since_start,
                'type' => $event->event_name
            ];
        }

        // Lets go through the second groups and look for events we can group together
        $skipNextType = null;
        $tickedImpulses = [];
        foreach ($combined as $time_key => &$time)
        {
            foreach ($time as $user_id => &$events)
            {
                if ($user_id == "stats")
                {
                    continue;
                }

                /** @var $event MatchEvent */
                foreach ($events as $key => &$event)
                {
                    if ($skipNextType != null && $skipNextType == $event->event_name)
                    {
                        unset($combined[$time_key][$user_id][$key]);
                        continue;
                    }

                    if ($event->event_name == EventName::WeaponPickupPad)
                    {
                        $skipNextType = EventName::WeaponPickup;
                    }
                    else if ($event->event_name == EventName::Medal)
                    {

                    }
                    else if ($event->event_name == EventName::Impulse)
                    {
                        if (MetadataType::isTickingImpulse($event->killer_weapon_id))
                        {
                            if (isset($tickedImpulses[$event->killer_id][$event->killer_weapon_id]))
                            {
                                $tickedImpulses[$event->killer_id][$event->killer_weapon_id]['lastSecond'] = $time_key;
                            }
                            else
                            {
                                $tickedImpulses[$event->killer_id][$event->killer_weapon_id]['startSecond'] = $time_key;
                                $tickedImpulses[$event->killer_id][$event->killer_weapon_id]['lastSecond'] = $time_key;
                                $tickedImpulses[$event->killer_id][$event->killer_weapon_id]['event'] = $event;
                            }
                            unset($combined[$time_key][$user_id][$key]);
                        }
                    }
                }
            }

            foreach ($tickedImpulses as $user_id => $data)
            {
                foreach ($data as $uuid => $item)
                {
                    if ($item['lastSecond'] != $time_key)
                    {
                        // This impulse no longer exists. End it here and insert the last one acquired
                        $item['event']['totalTime'] = $item['lastSecond'] - $item['startSecond'];
                        $combined[$time_key][$user_id][] = $item['event'];
                        unset($tickedImpulses[$item['event']->killer_id][$item['event']->killer_weapon_id]);
                    }
                }
            }
        }

        // Final Cleanup, remove times that have no events
        $deleteEvent = true;
        foreach ($combined as $time => $events)
        {
            if (! is_array($events))
            {
                unset($combined[$time]);
                continue;
            }

            foreach ($events as $user_id => $user_events)
            {
                if ($user_id !== "stats" && count($user_events) > 0)
                {
                    $deleteEvent = false;
                }

                if (count($user_events) == 0)
                {
                    unset($combined[$time][$user_id]);
                }
            }

            if ($deleteEvent)
            {
                unset($combined[$time]);
            }

            $deleteEvent = true;
        }

        \Cache::put($cacheKey, $combined, 60 * 24 * 7); // 60m * 24h * 7days
        return $combined;
    }

    /**
     * @param $match Match
     * @return array
     */
    public static function buildQuickGameStats($match)
    {
        $combined = [
            'vip' => [
                'title' => 'Match VIP',
                'tooltip' => 'Who the game thought was the best spartan',
                'message' => 'Game VIP',
                'spartan' => null,
            ],
            'kd' => [
                'title' => 'KD',
                'tooltip' => 'Best KD Ratio (Kills / Deaths)',
                'message' => 'Highest KD Ratio',
                'spartan' => null,
            ],
            'kda' => [
                'title' => 'KDA',
                'tooltip' => 'Best KDA Ratio ( ( Kills + Assists ) / Deaths)',
                'message' => 'Highest KDA Ratio',
                'spartan' => null,
            ],
            'kills' => [
                'title' => 'Kills',
                'tooltip' => 'Most Kills in Match',
                'message' => 'Most Kills',
                'spartan' => null,
            ],
            'loser' => [
                'title' => 'Most Deaths',
                'tooltip' => 'The unfortunate spartan to die the most in this match.',
                'message' => 'Sir. Dies-a-lot',
                'spartan' => null,
            ],
            'deaths' => [
                'title' => 'Deaths',
                'tooltip' => 'The spartan who died the least in this match.',
                'message' => 'Least Deaths',
                'spartan' => null,
            ],
            'assists' => [
                'title' => 'Total Assists',
                'tooltip' => 'The spartan who got the most assists',
                'message' => 'Team Helper',
                'spartan' => null
            ],
            'medals' => [
                'title' => 'Medals',
                'tooltip' => 'The spartan who collected the most medals in this match.',
                'message' => 'Medal Collector',
                'spartan' => null,
            ],
            'damage' => [
                'title' => 'Damage',
                'tooltip' => 'The spartan who dealt the most damage in this match.',
                'message' => 'Maximum Damage',
                'spartan' => null,
            ],
            'avgtime' => [
                'title' => 'Average Time',
                'tooltip' => 'The spartan who had the longest average lifespan.',
                'message' => 'Longest Average Lifespan',
                'spartan' => null,
            ],
            'groundpound' => [
                'title' => 'Groundpound',
                'tooltip' => 'The spartan who got the most groundpounds',
                'message' => 'Falling Anvil',
                'spartan' => null,
                'zero' => true,
            ],
            'noscoper' => [
                'title' => 'NoScoper',
                'tooltip' => 'The spartan who got the most no-scopes in this match',
                'message' => 'NoScoper',
                'spartan' => null,
                'zero' => true
            ],
            'sniper' => [
                'title' => 'Sniper',
                'tooltip' => 'The spartan with the most snipes in this match.',
                'message' => 'Sniper',
                'spartan' => null,
                'zero' => true
            ],
            'assassin' => [
                'title' => 'Assassin',
                'tooltip' => 'The spartan with the most assassinations in this match.',
                'message' => 'Mr. Sneaks',
                'spartan' => null,
                'zero' => true
            ],
            'aikiller' => [
                'title' => 'AI Killer',
                'tooltip' => 'The spartan who killed the most AI in this match.',
                'message' => 'AI Killer',
                'spartan' => null,
                'zero' => true,
            ],
            'beater' => [
                'title' => 'Melee Kills',
                'tooltip' => 'The spartan who beat down (melee) the most spartans in this match.',
                'message' => 'Beater',
                'spartan' => null,
                'zero' => true,
            ],
            'powerholder' => [
                'title' => 'Power Weapon Held Time',
                'tooltip' => 'The spartan who held power weapons the longest',
                'message' => 'Power Weapon Hogger',
                'spartan' => null,
            ],
            'highest_rank' => [
                'title' => 'Highest Spartan Rank',
                'tooltip' => 'The spartan with highest Spartan Rank',
                'message' => 'Highest Spartan Rank',
                'spartan' => null,
            ],
            'grenade_spammer' => [
                'title' => 'Total Grenade Kills',
                'tooltip' => 'The spartan with the most grenade kills',
                'message' => 'Nade Spammer',
                'spartan' => null,
                'zero' => true,
            ],
            'accurate_shot' => [
                'title' => 'Accurate Shot',
                'tooltip' => 'The spartan who fired the most shots accurately. (Landed / Fired).',
                'message' => 'Accurate Shot',
                'spartan' => null
            ],
        ];

        foreach ($match->players as $player)
        {
            if ($player->dnf == 1) continue;

            self::checkOrSet($combined['vip'], $player, 'rank', false);
            self::checkOrSet($combined['kd'], $player, 'kd', true);
            self::checkOrSet($combined['kda'], $player, 'kad', true);
            self::checkOrSet($combined['kills'], $player, 'totalKills', true);
            self::checkOrSet($combined['loser'], $player, 'totalDeaths', true);
            self::checkOrSet($combined['deaths'], $player, 'totalDeaths', false);
            self::checkOrSet($combined['assists'], $player, 'totalAssists', true);
            self::checkOrSet($combined['damage'], $player, function($player) {
                return round($player->weapon_dmg, 2);
            }, true);
            self::checkOrSet($combined['avgtime'], $player, 'avg_lifestime', true);
            self::checkOrSet($combined['groundpound'], $player, 'totalGroundPounds', true);
            self::checkOrSet($combined['assassin'], $player, 'totalAssassinations', true);
            
            self::checkOrSet($combined['medals'], $player, function($player) {
                return collect($player->medals)->sum('count');
            }, true);

            self::checkOrSet($combined['noscoper'], $player, function ($player) {
                return self::getMedalCount($player, self::MEDAL_NOSCOPE_UUID);
            }, true);

            self::checkOrSet($combined['sniper'], $player, function ($player) {
                return self::getMedalCount($player, [self::MEDAL_SNIPER_UUID, self::MEDAL_SNIPER_HEAD_UUID]);
            }, true);

            self::checkOrSet($combined['accurate_shot'], $player, function($player) {
                if ($player->shots_fired == 0)
                {
                    return $player->shots_fired;
                }
                return round((($player->shots_landed / $player->shots_fired) * 100), 2) ."%";
            }, true);

            self::checkOrSet($combined['aikiller'], $player, 'totalAiKills', true);
            self::checkOrSet($combined['beater'], $player, 'totalMeleeKills', true);
            self::checkOrSet($combined['powerholder'], $player, 'totalPowerWeaponTime', true);
            self::checkOrSet($combined['highest_rank'], $player, 'spartanRank', true);
            self::checkOrSet($combined['grenade_spammer'], $player, 'totalGrenadeKills', true);
        }

        return [
            'top' => $combined,
            'funny' => null
        ];
    }

    /**
     * @param $combined mixed
     * @param $player MatchPlayer
     * @param $key string
     * @param $high boolean (sort by high)
     */
    private static function checkOrSet(&$combined, $player, $key, $high = true)
    {
        if ($combined['spartan'] == null)
        {
            self::set($combined, $player, $key);
        }
        else
        {
            if ($high)
            {
                if (self::get($combined['spartan'], $key) < self::get($player, $key))
                {
                    self::set($combined, $player, $key);
                }
            }
            else
            {
                if (self::get($combined['spartan'], $key) > self::get($player, $key))
                {
                    self::set($combined, $player, $key);
                }
            }
        }
    }

    /**
     * @param $combined array
     * @param $player MatchPlayer
     * @param $key string
     * @return void
     */
    private static function set(&$combined, $player, $key)
    {
        $combined['spartan'] = $player;
        $combined['value'] = self::get($player, $key);
        $combined['formatted'] = self::get($player, $key, true);
    }

    /**
     * @param $player MatchPlayer
     * @param $key string|callable
     * @param $formatted boolean
     * @return mixed
     */
    private static function get($player, $key, $formatted = false)
    {
        if (is_callable($key))
        {
            return call_user_func($key, $player);
        }
        else if (method_exists($player, $key))
        {
            return $player->$key();
        }
        else
        {
            if ($formatted)
            {
                return $player->$key;
            }
            return $player->getOriginal($key);
        }
    }

    /**
     * @param $player MatchPlayer
     * @param $keys array
     * @return mixed
     */
    private static function getMedalCount($player, $keys)
    {
        return collect($player->medals)
            ->only($keys)
            ->sum('count');
    }
}