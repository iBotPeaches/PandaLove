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
            'start'  => 'November 1, 2017',
            'end'    => 'December 29, 2017',
            'season' => 7,
        ],
        8 => [
            'start'  => 'December 31, 2017',
            'end'    => 'February 25, 2018',
            'season' => 8,
        ],
        9 => [
            'start'  => 'February 28, 2018',
            'end'    => 'April 27, 2018',
            'season' => 9,
        ],
        10 => [
            'start'  => 'April 30, 2018',
            'end'    => 'June 26, 2018',
            'season' => 10,
        ],
        11 => [
            'start'  => 'June 28, 2018',
            'end'    => 'August 28, 2018',
            'season' => 11,
        ],
        12 => [
            'start'  => 'August 31, 2018',
            'end'    => 'October 28, 2018',
            'season' => 12,
        ],
        13 => [
            'start'  => 'November 1, 2018',
            'end'    => 'December 31, 2018',
            'season' => 13,
        ],
        14 => [
            'start'  => 'January 1, 2019',
            'end'    => 'February 28, 2019',
            'season' => 14,
        ],
        15 => [
            'start'  => 'March 1, 2019',
            'end'    => 'April 29, 2019',
            'season' => 15,
        ],
        16 => [
            'start'  => 'May 1, 2019',
            'end'    => 'July 30, 2019',
            'season' => 16,
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
