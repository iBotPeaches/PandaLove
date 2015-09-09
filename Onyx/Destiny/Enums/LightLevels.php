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

        if ($level == self::$MAX_LEVEL || $character->progress_exp == 0)
        {
            return [
                'max' => $character->next_level_exp,
                'value' => $character->next_level_exp,
                'message' => 'Max Level Reached',
                'isMax' => true
            ];
        }
        else
        {
            return [
                'max' => $character->next_level_exp,
                'value' => $character->progress_exp,
                'percent' => $character->next_level_exp / $character->progress_exp,
                'message' => 'Level ' . $character->level . '. Progress to Level ' . ($character->level + 1),
                'isMax' => false
            ];
        }
    }
}