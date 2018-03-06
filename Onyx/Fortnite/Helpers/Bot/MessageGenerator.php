<?php

namespace Onyx\Fortnite\Helpers\Bot;

use Onyx\Account;
use Onyx\Fortnite\Objects\Stats;

class MessageGenerator
{
    /**
     * @var array
     */
    private static $ignoredAttributes = ['id', 'epic_id', 'account_id', 'user_id', 'solo_lastmodified',
        'duo_lastmodified', 'squad_lastmodified', 'created_at', 'updated_at', 'inactiveCounter', ];

    /**
     * @param Account $account
     * @param Stats   $old
     * @param Stats   $new
     *
     * @return string
     */
    public static function buildOverwatchUpdateMessage(Account $account, Stats $old, Stats $new)
    {
        $msg = '';

        $stats = [];
        $random_keys = array_rand($old->getAttributes(), count($old->getAttributes()));
        shuffle($random_keys);

        foreach ($random_keys as $random_key) {
            if (!in_array($random_key, self::$ignoredAttributes) && count($stats) < 3) {
                $difference = $new->$random_key - $old->$random_key;

                if ($difference != 0) {
                    $stats[$random_key] = $difference;
                }
            }
        }

        // If no stats were changed, just grab 3.
        if (count($stats) === 0) {
            foreach ($random_keys as $random_key) {
                if (!in_array($random_key, self::$ignoredAttributes) && count($stats) < 3) {
                    $difference = $new->$random_key - $old->$random_key;
                    $stats[$random_key] = $difference;
                }
            }
        }

        $gameDifference = $new->getMatchesSum() - $old->getMatchesSum();

        $msg .= '<strong>'.$account->gamertag.'</strong> stats have been updated in <strong>'.$gameDifference.'</strong> games.<br />';

        if ($old->solo_top1 < $new->solo_top1) {
            $diff = $new->solo_top1 - $old->solo_top1;
            $msg .= 'Holy shit. We have <strong>'.$diff.'</strong> '.'new SOLO TOP 1 (#1) FIRST PLACE'.'<br />';
        }

        if ($old->duo_top1 < $new->duo_top1) {
            $diff = $new->duo_top1 - $old->duo_top1;
            $msg .= 'Wow. <strong>'.$diff.'</strong> new 1st place in DUOS. NICE!!!!'.'<br />';
        }

        if ($old->squad_top1 < $new->squad_top1) {
            $diff = $new->squad_top1 - $old->squad_top1;
            $msg .= 'PANDAS GETTING ANOTHER <strong>'.$diff.'</strong> SQUAD WIN(s). #PNDA'.'<br />';
        }

        $msg .= '<br />Random Stats:<br />';
        foreach ($stats as $key => $difference) {
            $msg .= ucfirst(str_replace('_', ' ', $key)).': ';
            $msg .= $new->$key.' ('.sprintf('%+d', $difference).') <br />';
        }

        return $msg;
    }
}
