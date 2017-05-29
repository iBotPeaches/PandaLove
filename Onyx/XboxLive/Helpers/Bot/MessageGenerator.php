<?php

namespace Onyx\XboxLive\Helpers\Bot;

use Carbon\Carbon;

class MessageGenerator
{
    /**
     * @var string
     */
    public static $central = 'everyone';

    /**
     * @var string
     */
    public static $eastern = 'connor';

    /**
     * @var string
     */
    public static $pacific = 'selkies/kevin';

    /**
     * @var string
     */
    public static $mountain = 'piece';

    /**
     * @var string
     */
    public static $gmt = 'pele';

    /**
     * @return string
     */
    public static function buildTimezonesMessage()
    {
        $cst = new Carbon('now', 'America/Chicago');
        $est = new Carbon('now', 'America/New_York');
        $pac = new Carbon('now', 'America/Los_Angeles');
        $mnt = new Carbon('now', 'America/Phoenix');
        $gmt = new Carbon('now', 'Etc/GMT');

        $msg = '<strong>Timezones of Pandas</strong><br /><br />';

        $msg .= '<strong>'.self::$pacific.'</strong> - '.$pac->format('g:ia - M j').'<br />';
        $msg .= '<strong>'.self::$mountain.'</strong> - '.$mnt->format('g:ia - M j').'<br />';
        $msg .= '<strong>'.self::$central.'</strong> - '.$cst->format('g:ia - M j').'<br />';
        $msg .= '<strong>'.self::$eastern.'</strong> - '.$est->format('g:ia - M j').'<br />';
        $msg .= '<strong>'.self::$gmt.'</strong> - '.$gmt->format('g:ia - M j').'<br />';

        return $msg;
    }
}
