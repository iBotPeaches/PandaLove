<?php namespace Onyx\XboxLive\Helpers\Bot;

use Carbon\Carbon;

class MessageGenerator {

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
    public static $pacific = 'selkies';

    /**
     * @var string
     */
    public static $mountain = 'piece';

    /**
     * @return string
     */
    public static function buildTimezonesMessage()
    {
        $cst = new Carbon('now', 'America/Chicago');
        $est = new Carbon('now', 'America/New_York');
        $pac = new Carbon('now', 'America/Los_Angeles');
        $mnt = new Carbon('now', 'America/Phoenix');

        $msg = '<strong>Timezones of Pandas</strong><br /><br />';

        $msg .= '<strong>' . self::$pacific . '</strong> - ' . $pac->format('g:ia') . '<br />';
        $msg .= '<strong>' . self::$mountain . '</strong> - ' . $mnt->format('g:ia') . '<br />';
        $msg .= '<strong>' . self::$central . '</strong> - ' . $cst->format('g:ia') . '<br />';
        $msg .= '<strong>' . self::$eastern . '</strong> - ' . $est->format('g:ia') . '<br />';

        return $msg;
    }
}