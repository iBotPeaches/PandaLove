<?php namespace Onyx\Halo5\Helpers\Utils;

use Onyx\Halo5\Objects\Match;
use Onyx\Halo5\Objects\MatchPlayer;

class Game {

    /**
     * UUID for No scope award -- Snapshot medal
     */
    const MEDAL_NOSCOPE_UUID = '1986137636';

    /**
     * UUID for Sniper award -- Sniper Kill medal
     */
    const MEDAL_SNIPER_UUID = '775545297';

    /*
     * UUID for Sniper award -- Sniper Headshot medal
     */
    const MEDAL_SNIPER_HEAD_UUID = '848240062';

    /*
     * UUID for Groundpound -- Groundpound medal
     */
    const MEDAL_GROUNDPOUND_UUID = '492192256';

    /*
     * UUID for Assassination -- Assassination medal
     */
    const MEDAL_ASSASSIN_UUID = '2966496172';

    /*
     * UUID for Airsassination -- Airsassination
     */
    const MEDAL_AIRSASSIN_UUID = '2006781774';

    /**
     * @param $match Match
     * @return array
     */
    public static function buildQuickGameStats($match)
    {
        $combined = [
            'kd' => [
                'key' => 'kd',
                'title' => 'KD',
                'message' => 'Highest KD Ratio',
                'spartan' => null,
                'value' => null
            ],
            'kda' => [
                'key' => 'kda',
                'title' => 'KDA',
                'message' => 'Highest KDA Ratio',
                'spartan' => null,
                'value' => null
            ],
            'kills' => [
                'key' => 'kills',
                'title' => 'Kills',
                'message' => 'Most Kills',
                'spartan' => null,
                'value' => null,
            ],
            'loser' => [
                'key' => 'loser',
                'title' => 'Most Deaths',
                'message' => 'Sir. Dies-a-lot',
                'spartan' => null,
                'value' => null,
            ],
            'deaths' => [
                'key' => 'deaths',
                'title' => 'Deaths',
                'message' => 'Least Deaths',
                'spartan' => null,
                'value' => null,
            ],
            'medals' => [
                'key' => 'medals',
                'title' => 'Medals',
                'message' => 'Most Medals Obtained',
                'spartan' => null,
                'value' => null
            ],
            'damage' => [
                'key' => 'damage',
                'title' => 'Damage',
                'message' => 'Maximum Damage',
                'spartan' => null,
                'value' => null
            ],
            'avgtime' => [
                'key' => 'avgtime',
                'title' => 'Average Time',
                'message' => 'Longest Average Lifespan',
                'spartan' => null,
                'value' => null
            ],
            'groundpound' => [
                'key' => 'groundpound',
                'title' => 'Groundpound',
                'message' => 'Falling Anvil',
                'spartan' => null,
                'value' => null
            ],
            'noscoper' => [
                'key' => 'noscoper',
                'title' => 'NoScoper',
                'message' => 'NoScoper',
                'spartan' => null,
                'value' => null,
                'zero' => true
            ],
            'sniper' => [
                'key' => 'sniper',
                'title' => 'Sniper',
                'message' => 'Sniper',
                'spartan' => null,
                'value' => null,
                'zero' => true
            ],
            'assassin' => [
                'key' => 'assassin',
                'title' => 'Assassin',
                'message' => 'Mr. Sneaks',
                'spartan' => null,
                'value' => null,
                'zero' => true
            ]
        ];

        foreach ($match->players as $player)
        {
            if ($player->dnf == 1) continue;
            
            self::checkOrSet($combined['kd'], $player, 'kd', true);
            self::checkOrSet($combined['kda'], $player, 'kad', true);
            self::checkOrSet($combined['kills'], $player, 'totalKills', true);
            self::checkOrSet($combined['loser'], $player, 'totalDeaths', true);
            self::checkOrSet($combined['deaths'], $player, 'totalDeaths', false);
            self::checkOrSet($combined['damage'], $player, 'weapon_dmg', true);
            self::checkOrSet($combined['avgtime'], $player, 'avg_lifestime', true);

            self::checkOrSet($combined['medals'], $player, function($player) {
                return collect($player->medals)->sum('count');
            }, true);

            self::checkOrSet($combined['noscoper'], $player, function ($player) {
                return self::getMedalCount($player, self::MEDAL_NOSCOPE_UUID);
            }, true);

            self::checkOrSet($combined['sniper'], $player, function ($player) {
                return self::getMedalCount($player, [self::MEDAL_SNIPER_UUID, self::MEDAL_SNIPER_HEAD_UUID]);
            }, true);

            self::checkOrSet($combined['groundpound'], $player, function ($player) {
                return self::getMedalCount($player, self::MEDAL_GROUNDPOUND_UUID);
            }, true);

            self::checkOrSet($combined['assassin'], $player, function ($player) {
                return self::getMedalCount($player, [self::MEDAL_ASSASSIN_UUID, self::MEDAL_AIRSASSIN_UUID]);
            }, true);
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

    private static function getMedalCount($player, $keys)
    {
        return collect($player->medals)
            ->only($keys)
            ->sum('count');
    }
}