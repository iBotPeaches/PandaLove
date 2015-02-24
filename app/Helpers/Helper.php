<?php namespace Helpers;

class Helper {

    private $team_id_to_string = array(
        16 => 'Alpha',
        17 => 'Bravo'
    );

    public function teamIdToString($id)
    {
        if(isset($team_id_to_string[$id]))
        {
            return $team_id_to_string[$id];
        }
    }
}

