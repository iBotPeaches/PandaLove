<?php namespace Onyx\Halo5\Enums;

abstract class GameResult {

    /**
     * DidNotFinish
     *
     * The player was not present when the match ended.
     */
    const DidNotFinish = 0;

    /**
     * Lost
     *
     * The player was on a team that was assigned a loss, typically this is when a team does not have rank = 1.
     */
    const Lost = 1;

    /**
     * Tied
     *
     * The player was on the team that was awarded a tie. Typically this is when the player is on the team with rank
     * = 1, and there is at least one other team with rank = 1.
     */
    const Tied = 2;

    /**
     * Won
     *
     * The player was on the team that was assigned the win, typically this is the team that has rank = 1.
     */
    const Won = 3;

    /**
     * @param $id
     * @return string
     */
    public static function getColor($id)
    {
        switch ($id)
        {
            case self::DidNotFinish:
                return 'yellow';

            case self::Lost:
                return 'red';

            case self::Won:
                return 'green';

            case self::Tied:
                return 'grey';
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function getIcon($id)
    {
        switch ($id)
        {
            case self::DidNotFinish:
                return 'meh';

            case self::Lost:
                return 'frown';

            case self::Won:
                return 'smile';

            case self::Tied:
                return 'meh';
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function getTitle($id)
    {
        switch ($id)
        {
            case self::DidNotFinish:
                return 'Quit Out';

            case self::Lost:
                return 'Loss';

            case self::Won:
                return 'Victory';

            case self::Tied:
                return 'Tied';
        }
    }
}