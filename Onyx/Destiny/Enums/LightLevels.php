<?php namespace Onyx\Destiny\Enums;

class LightLevels {

    /**
     * @var int
     */
    public static $MAX_LIGHT = 300;

    /**
     * @var int
     */
    public static $MAX_LEVEL = 40;

    /**
     * @param \Onyx\Destiny\Objects\Character $character
     * @return array
     */
    public static function percentageToNextLevel($character)
    {
        $level = $character->level;

        if ($level == self::$MAX_LEVEL)
        {
            return [
                'max' => self::$MAX_LIGHT,
                'light' => self::$MAX_LIGHT,
                'message' => 'Max Level Reached'
            ];
        }
        else
        {
            return [
                'max' => self::$MAX_LIGHT,
                'light' => $character->light,
                'percent' => 20,
                'message' => 'Level ' . $character->level . '. Progress to Level ' . ($character->level + 1)
            ];
        }
    }
}