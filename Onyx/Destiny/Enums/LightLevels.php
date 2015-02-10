<?php namespace Onyx\Destiny\Enums;

class LightLevels {

    /**
     * @var array
     * @url https://destinytracker.com/Forums/Post/4227/2/re-how-much-light-is-needed-per-level
     */
    public static $levels = [
        21 => 21,
        22 => 32,
        23 => 43,
        24 => 54,
        25 => 65,
        26 => 76,
        27 => 87,
        28 => 98,
        29 => 109,
        30 => 120,
        31 => 132,
        32 => 144
    ];

    /**
     * @param \Onyx\Destiny\Objects\Character $character
     * @return array
     */
    public static function percentageToNextLevel($character)
    {
        $light = $character->light;

        if ($light == 144)
        {
            return [
                'max' => 144,
                'light' => 144,
                'message' => 'Max Level Reached'
            ];
        }
        else
        {
            $nearest = self::findNearestValue(self::$levels, $light);

            return [
                'max' => $nearest,
                'light' => $light,
                'percent' => $light / $nearest,
                'message' => 'Progress to Level ' . ($character->level + 1)
            ];
        }
    }

    /**
     * @param $arr
     * @param $value
     * @return null
     */
    private static function findNearestValue($arr, $value)
    {
        $nearest = null;

        foreach($arr as $item)
        {
            if ($nearest == null || abs($value - $nearest) > abs($item - $value))
            {
                $nearest = $item;
            }
        }

        return $nearest;
    }
}