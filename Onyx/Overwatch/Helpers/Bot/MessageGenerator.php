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

        $random_key = array_rand($old->getAttributes(), 1);

        while ($random_key == 'avatar' || $random_key == 'rank_image' || $random_key == 'account_id' || $random_key == 'season' || $random_key == 'id') {
            $random_key = array_rand($old->getAttributes(), 1);
        }

        $difference = $new->$random_key - $old->$random_key;

        $msg .= '<strong>'.$account->gamertag.'</strong> stats have been updated!<br />';
        $msg .= 'Level: <strong>' . $new->totalLevel() . "</strong><br />";
        $msg .= 'SR (current/high): <strong>' . $new->comprank . ' / ' . $new->max_comprank . '</strong><br />';

        $msg .= '<br />Random Stat:<br />';
        $msg .= ucfirst(str_replace('_', ' ', $random_key)) . ': ';
        $msg .= $new->$random_key . ' (' . sprintf("%+d", $difference) . ')';

        return $msg;
    }
}
