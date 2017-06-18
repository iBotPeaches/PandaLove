<?php

namespace Onyx\Overwatch\Helpers\Bot;

use Onyx\Account;
use Onyx\Overwatch\Objects\Stats;

class MessageGenerator
{
    /**
     * @var array
     */
    private static $ignoredAttributes = ['avatar', 'rank_image', 'account_id', 'season', 'id', 'created_at', 'updated_at'];

    /**
     * @param Account $account
     * @param Stats $old
     * @param Stats $new
     * @return string
     */
    public static function buildOverwatchUpdateMessage($account, $old, $new)
    {
        $msg = '';

        $stats = [];
        $random_keys = array_rand($old->getAttributes(), count($old->getAttributes()));
        shuffle($random_keys);

        foreach ($random_keys as $random_key) {
            if (! in_array($random_key, self::$ignoredAttributes) && count($stats) < 3) {
                $difference = $new->$random_key - $old->$random_key;

                if ($difference != 0) {
                    $stats[$random_key] = $difference;
                }
            }
        }

        // If no stats were changed, just grab 3.
        if (count($stats) === 0) {
            foreach ($random_keys as $random_key) {
                if (! in_array($random_key, self::$ignoredAttributes) && count($stats) < 3) {
                    $difference = $new->$random_key - $old->$random_key;
                    $stats[$random_key] = $difference;
                }
            }
        }

        $msg .= '<strong>'.$account->gamertag.'</strong> stats have been updated!<br />';
        $msg .= 'Level: <strong>' . $new->totalLevel() . "</strong><br />";
        $msg .= 'SR (current/high): <strong>' . $new->comprank . ' / ' . $new->max_comprank . '</strong><br />';

        $msg .= '<br />Random Stats:<br />';
        foreach ($stats as $key => $difference) {
            $msg .= ucfirst(str_replace('_', ' ', $key)) . ': ';
            $msg .= $new->$key . ' (' . sprintf("%+d", $difference) . ') <br />';
        }

        return $msg;
    }
}
