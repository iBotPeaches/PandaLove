<?php

namespace Onyx\Halo5\Helpers\Date;

class DateHelper
{
    /**
     * Creates a patched DateTimeInterval (due to milliseconds), adds it to an empty date. Thus getTimestamp()
     * returns seconds passed.
     *
     * @param $value
     * @url http://stackoverflow.com/questions/14277611/convert-dateinterval-object-to-seconds-in-php
     *
     * @return int
     */
    public static function returnSeconds($value)
    {
        $date = new DateIntervalFractions($value);

        return date_create('@0')->add($date)->getTimestamp();
    }
}
