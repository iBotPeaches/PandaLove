<?php

namespace Onyx\Overwatch\Helpers\String;

use Illuminate\Support\Str;

/**
 * Class Text.
 */
class Text
{
    /**
     * @var int
     */
    public static $MINS_IN_HOUR = 60;

    /**
     * @param string $word
     *
     * @return string
     */
    public static function label(string $word)
    {
        return str_replace('_', ' ', title_case($word));
    }

    /**
     * @param string $stat
     * @param string $value
     *
     * @return string
     */
    public static function heuristicFormat(string $stat, string $value) : string
    {
        // Accuracy needs to be in percent.
        if (Str::contains($stat, 'accuracy')) {
            return (string) ($value * 100).'%';
        }

        // This is an already formatted average, just return
        if (Str::contains($value, '.')) {
            return (string) $value;
        }

        return (string) number_format($value);
    }

    /**
     * @param string $playtime
     *
     * @return string
     */
    public static function playtimeFormat(string $playtime) : string
    {
        $playtime = (float) $playtime;

        if ($playtime < 1) {
            return (self::$MINS_IN_HOUR * $playtime).' mins';
        }

        return $playtime.' hour(s)';
    }
}
