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
        9 => 'ThreeVSThree', // Skirmish
        10 => 'Control',
        11 => 'Lockdown', // Salvage
        12 => 'Team', // Clash
        13 => 'FreeForAll', // Rumble
        14 => 'TrialsOfOsiris',
        15 => 'Doubles',
        16 => 'Nightfall',
        17 => 'Heroic',
        18 => 'AllStrikes',
        19 => 'IronBanner',
        20 => 'AllArena',
        21 => 'Arena',
        22 => 'ArenaChallenge',
        23 => 'Elimination',
        24 => 'Rift',
        25 => 'AllMayhem',
        26 => 'MayhemClash',
        27 => 'MayhemRumble',
        28 => 'ZoneControl'
    ];

    /**
     * Checks if $id is a PVP event
     *
     * @param $id
     * @return bool
     */
    public static function isPVP($id)
    {
        $pvp = [5, 9, 10, 11, 12, 14, 15, 23, 24];

        if (in_array($id, $pvp))
        {
            return true;
        }

        return false;
    }

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