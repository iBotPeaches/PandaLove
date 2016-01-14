<?php namespace Onyx\Halo5\Helpers\Bot;

class MessageGenerator {

    /**
     * @param \Onyx\Account $account
     * @param \Onyx\Halo5\Objects\Data $old
     * @param \Onyx\Halo5\Objects\Data $new
     * @return string
     */
    public static function buildH5UpdateMessage($account, $old, $new)
    {
        $old_kd         = $old->kd();
        $old_kad        = $old->kad();
        $old_games      = $old->totalGames;

        $new_kd         = $new->kd();
        $new_kad        = $new->kad();
        $new_games      = $new->totalGames;

        $msg = '<strong>' . $account->gamertag . '</strong> stats have been updated!<br /><br />';

        $msg .= 'KD went from ' . $old_kd . ' to ' . $new_kd . ' <br />';
        $msg .= 'KAD went from ' . $old_kad . ' to ' . $new_kad . ' <br />';
        $msg .= 'Total Games played went from ' . $old_games . ' to ' . $new_games . '<br />';

        return $msg;
    }
}