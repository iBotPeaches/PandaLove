<?php namespace Onyx\Halo5\Enums;

abstract class EventName {

    /**
     * Unknown
     *
     * Not possible.
     */
    const Unknown = 0;

    /**
     * Death
     */
    const Death = 1;

    /**
     * @param $name
     * @return int
     * @throws \Exception
     */
    public static function getId($name)
    {
        switch ($name)
        {
            case "Death":
                return self::Death;

            default:
                throw new \Exception($name . ' Could not be found.');
        }
    }
}