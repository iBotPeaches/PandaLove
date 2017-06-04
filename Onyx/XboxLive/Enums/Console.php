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
     * PC
     *
     * On xbox
     */
    const PC = 3;

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getOverwatchTag($id)
    {
        switch ($id)
        {
            case Console::Xbox:
                return 'xbl';

            case Console::PSN:
                return 'psn';

            case Console::PC:
                return 'pc';

            default:
                throw new \Exception('Unknown ID - ' . $id);
        }
    }
}
