<?php namespace Onyx\Destiny\Helpers\Utils;

use Illuminate\Support\Facades\Log;

class Gametype {

    /**
     * @var array
     */
    public static $gametype_ids = [
        0 => 'None',
        1 => 'Unknown', // unknown
        2 => 'Story',
        3 => 'Strike',
        4 => 'Raid',
        5 => 'AllPvP',
        6 => 'Patrol',
        7 => 'AllPvE',
        8 => 'PvPIntroduction',
        9 => 'Skirmish',
        10 => 'Control',
        11 => 'Salvage',
        12 => 'Clash',
        13 => 'Rumble',
        14 => 'Unknown', // unknown
        15 => 'Doubles Skirmish'
    ];

    /**
     * @param $id
     * @return string
     */
    public static function getGametype($id)
    {
        $id = intval($id);

        if (isset(self::$gametype_ids[$id]))
        {
            return self::$gametype_ids[$id];
        }
        else
        {
            Log::warning('Unknown gametype id ' . $id);
            return 'Unknown';
        }
    }
}