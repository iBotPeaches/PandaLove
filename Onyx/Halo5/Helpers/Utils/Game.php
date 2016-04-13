<?php namespace Onyx\Halo5\Helpers\Utils;

use Onyx\Halo5\Objects\Match;
use Onyx\Halo5\Objects\MatchPlayer;

class Game {

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
            ]
        ];

        foreach ($match->players as $player)
        {
            if ($player->dnf == 1) continue;
            
            self::checkOrSet($combined['kd'], $player, 'kd', true);
            self::checkOrSet($combined['kda'], $player, 'kad', true);
            self::checkOrSet($combined['kills'], $player, 'totalKills', true);
            self::checkOrSet($combined['deaths'], $player, 'totalDeaths', false);

            self::checkOrSet($combined['medals'], $player, function($player) {
                $count = 0;

                foreach ($player->medals as $medal) {
                    $count += $medal['count'];
                }
                return $count;
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
    }

    /**
     * @param $player MatchPlayer
     * @param $key string|callable
     * @return mixed
     */
    private static function get($player, $key)
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
            return $player->getOriginal($key);
        }
    }
}