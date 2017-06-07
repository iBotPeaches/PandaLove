<?php

namespace Onyx\Overwatch\Helpers\Bot;

use Onyx\Account;
use Onyx\Overwatch\Objects\Stats;

class MessageGenerator
{
    /**
     * @param Account $account
     * @param Stats $old
     * @param Stats $new
     * @return string
     */
    public static function buildOverwatchUpdateMessage($account, $old, $new)
    {
        $msg = '';

        return $msg;
    }
}
