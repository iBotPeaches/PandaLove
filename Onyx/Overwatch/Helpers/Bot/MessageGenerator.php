<?php

namespace Onyx\Overwatch\Helpers\Bot;

use Onyx\Account;
use Onyx\Overwatch\Helpers\Game\Character;
use Onyx\Overwatch\Objects\Stats;

class MessageGenerator
{
    /**
     * @var array
     */
    private static $ignoredAttributes = ['avatar', 'rank_image', 'account_id', 'prestige',
        'season', 'id', 'created_at', 'updated_at', 'inactive_counter', ];

    /**
     * @param Account $account
     * @param Stats   $old
     * @param Stats   $new
     * @param string  $char
     * @return string
     */
    public static function buildOverwatchUpdateMessage(Account $account, Stats $old, Stats $new, string $char)
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

        $oldLevel = $old->totalLevel();
        $newLevel = $new->totalLevel();
        $additionalGames = $new->games_played - $old->games_played;

        if ($oldLevel !== $newLevel) {
            $msg .= '<strong>'.$account->gamertag.'</strong> has leveled up in '.$additionalGames.' games.'.'<br />';
        } else {
            $msg .= '<strong>'.$account->gamertag.'</strong> stats have been updated!<br />';
        }

        $msg .= 'Level: <strong>'.$new->totalLevel().'</strong><br />';
        $msg .= 'SR (current/high): <strong>'.$new->comprank.' / '.$new->max_comprank.'</strong><br />';

        $msg .= '<br />Random Stats:<br />';
        foreach ($stats as $key => $difference) {
            $msg .= ucfirst(str_replace('_', ' ', $key)).': ';
            $msg .= $new->$key.' ('.sprintf('%+d', $difference).') <br />';
        }

        $character = null;
        // Grab a random character
        if ($char !== 'unknown') {
            $char = Character::getValidCharacter($char);

            if ($char !== 'unknown') {
                $character = $new->specificCharacter($char);
            }
        }

        $character = $character ?? $new->randomCharacter();
        $msg .= '<br />' . $character->character . ' Stats: <br />';

        $random_keys = array_rand($character->heroStats(), count($character->heroStats()));
        shuffle($random_keys);
        $statCount = 0;

        foreach ($random_keys as $random_key) {
            if (!in_array($random_key, self::$ignoredAttributes) && $statCount < 3) {

                $msg .= ucfirst(str_replace('_', ' ', $random_key)).': ';
                $msg .= number_format($character->heroStats()[$random_key]) . "<br />";
                $statCount++;
            }
        }

        return $msg;
    }
}
