<?php

namespace Onyx\Laravel\Helpers;

class Text
{
    /**
     * @param $num
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function numberToWord($num)
    {
        switch ($num) {
            case 1: return 'one';
            case 2: return 'two';
            case 3: return 'three';
            case 4: return 'four';
            case 5: return 'five';
            case 6: return 'six';
            case 7: return 'seven';
            case 8: return 'eight';
            case 9: return 'nine';
            case 10: return 'ten';
            case 11: return 'eleven';
            case 12: return 'twelve';

            default:
                throw new \Exception('Number to name matching not found. Add a rule for: '.$num);
        }
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

    /**
     * Makes number Ordinal (suffix).
     *
     * @param $number
     * @url http://stackoverflow.com/a/3110033/455008
     *
     * @return string
     */
    public static function ordinal($number)
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number.'th';
        } else {
            return $number.$ends[$number % 10];
        }
    }
}
