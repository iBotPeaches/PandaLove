<?php namespace Onyx\Destiny\Helpers\Utils;

use Illuminate\Support\Facades\Log;

class Team {

    /**
     * @var array
     */
    private static $team_id_to_string = array(
        16 => 'Alpha',
        17 => 'Bravo'
    );

    /**
     * @return array
     */
    public static function getTeamIds()
    {
        return array_keys(self::$team_id_to_string);
    }

    /**
     * @param $id
     * @return string
     */
    public static function teamIdToString($id)
    {
        $id = intval($id);

        if (isset(self::$team_id_to_string[$id]))
        {
            return self::$team_id_to_string[$id];
        }
        else
        {
            Log::warning('Team ID: ' . intval($id) . ' is unknown.');
            return 'Unknown';
        }
    }
}

