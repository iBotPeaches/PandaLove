<?php

namespace Onyx\Overwatch\Helpers\Game;

use Carbon\Carbon;

/**
 * Class Season.
 */
class Season
{
    public static $seasons = [
        1 => [
            'start'  => 'June 28, 2016',
            'end'    => 'August 18, 2016',
            'season' => 1,
        ],
        2 => [
            'start'  => 'September 1, 2016',
            'end'    => 'November 23, 2016',
            'season' => 2,
        ],
        3 => [
            'start'  => 'December 1, 2016',
            'end'    => 'February 22, 2017',
            'season' => 3,
        ],
        4 => [
            'start'  => 'March 1, 2017',
            'end'    => 'May 29, 2017',
            'season' => 4,
        ],
        5 => [
            'start'  => 'June 1, 2017',
            'end'    => 'August 29, 2017',
            'season' => 5,
        ],
        6 => [
            'start'  => 'August 30, 2017',
            'end'    => 'October 30, 2017',
            'season' => 6,
        ],
        7 => [
            'start' => 'November 1, 2017',
            'end' => 'December 30, 2017',
            'season' => 7,
        ],
    ];

    /**
     * @param $date
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public static function getSeason($date)
    {
        foreach (self::$seasons as $season) {
            $startDate = new Carbon($season['start'], 'UTC');
            $endDate = new Carbon($season['end'], 'UTC');

            if ((new Carbon($date))->between($startDate, $endDate)) {
                return $season;
            }
        }

        throw new \Exception('Uh oh. We have no season for this date - '.$date);
    }
}
