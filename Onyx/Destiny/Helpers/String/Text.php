<?php

namespace Onyx\Destiny\Helpers\String;

use Illuminate\Support\Str;
use Onyx\Destiny\Constants;

class Text
{
    /**
     * @param $id
     *
     * @return string
     */
    public static function itemUrl($id)
    {
        return sprintf(Constants::$ggItem, $id);
    }

    /**
     * Returns seo friendly Gamertag.
     *
     * EX: iBotPeaches v5 -> ibotpeaches-v5
     *
     * @param $gamertag
     *
     * @return string
     */
    public static function seoGamertag($gamertag)
    {
        return Str::lower(str_replace(' ', '-', str_replace('"', null, str_replace("'", null, $gamertag))));
    }

    /**
     * Returns value greatest used.
     *
     * @param $set
     *
     * @return mixed
     */
    public static function array_mode($set)
    {
        $count = [];

        foreach ($set as $item) {
            if (isset($count[$item])) {
                $count[$item] += 1;
            } else {
                $count[$item] = 1;
            }
        }

        arsort($count);
        $maxes = array_keys($count, max($count));

        return $maxes[0];
    }

    /**
     * A function for making time periods readable.
     *
     * @author      Aidan Lister <aidan@php.net>
     *
     * @version     2.0.1
     *
     * @link        http://aidanlister.com/2004/04/making-time-periods-readable/
     *
     * @param       int     number of seconds elapsed
     * @param       string  which time periods to display
     * @param       bool    whether to show zero time periods
     *
     * @return string
     */
    public static function timeDuration($seconds, $use = null, $zeros = false)
    {
        // Define time periods
        $periods = [
            'years'     => 31556926,
            'Months'    => 2629743,
            'weeks'     => 604800,
            'days'      => 86400,
            'hours'     => 3600,
            'minutes'   => 60,
            'seconds'   => 1,
        ];

        // Break into periods
        $seconds = (float) $seconds;
        $segments = [];
        foreach ($periods as $period => $value) {
            if ($use && strpos($use, $period[0]) === false) {
                continue;
            }

            $count = floor($seconds / $value);
            if ($count == 0 && !$zeros) {
                continue;
            }

            $segments[strtolower($period)] = $count;
            $seconds = $seconds % $value;
        }

        // Build the string
        $string = [];
        foreach ($segments as $key => $value) {
            $segment_name = substr($key, 0, -1);
            $segment = $value.' '.$segment_name;
            if ($value != 1) {
                $segment .= 's';
            }
            $string[] = $segment;
        }

        return implode(', ', $string);
    }
}
