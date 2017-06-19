<?php

namespace Onyx\XboxLive\Enums;

abstract class Console
{
    /**
     * Xbox.
     *
     * On Xbox
     */
    const Xbox = 1;

    /**
     * PSN.
     *
     * On PSN
     */
    const PSN = 2;

    /**
     * PC.
     *
     * On xbox
     */
    const PC = 3;

    /**
     * @param $id
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function getOverwatchTag($id)
    {
        switch ($id) {
            case self::Xbox:
                return 'xbl';

            case self::PSN:
                return 'psn';

            case self::PC:
                return 'pc';

            default:
                throw new \Exception('Unknown ID - '.$id);
        }
    }
}
