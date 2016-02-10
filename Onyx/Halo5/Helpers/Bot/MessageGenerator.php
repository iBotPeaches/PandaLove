<?php namespace Onyx\Halo5\Helpers\Bot;

class MessageGenerator {

    /**
     * @param \Onyx\Account $account
     * @param \Onyx\Halo5\Objects\Data $old_arena
     * @param \Onyx\Halo5\Objects\Warzone $old_warzone
     * @param \Onyx\Halo5\Objects\Data $new
     * @return string
     */
    public static function buildH5UpdateMessage($account, $old_arena, $old_warzone, $new)
    {
        $old_kd         = $old_arena->kd();
        $old_kad        = $old_arena->kad();
        $old_games      = $old_arena->totalGames;

        $new_kd         = $new->kd();
        $new_kad        = $new->kad();
        $new_games      = $new->totalGames;

        $old_w_kd       = $old_warzone->kd();
        $old_w_kad      = $old_warzone->kad();
        $old_w_games    = $old_warzone->totalGames;

        $new_w_kd       = $new->warzone->kd();
        $new_w_kad      = $new->warzone->kad();
        $new_w_games    = $new->warzone->totalGames;

        $msg = '<strong>' . $account->gamertag . '</strong> stats have been updated!<br />';

        $msg .= '---- Arena ----<br />';

        $msg .= 'KD went from ' . $old_kd . ' to ' . $new_kd . ' <br />';
        $msg .= 'KDA went from ' . $old_kad . ' to ' . $new_kad . ' <br />';
        $msg .= 'Total Games played went from ' . $old_games . ' to ' . $new_games . '<br />';

        $msg .= '<br />---- Warzone ---- <br />';
        $msg .= 'KD went from ' . $old_w_kd  . ' to ' . $new_w_kd . ' <br />';
        $msg .= 'KDA went from ' . $old_w_kad . ' to ' . $new_w_kad . ' <br />';
        $msg .= 'Total Games played went from ' . $old_w_games . ' to ' . $new_w_games . '<br />';

        return $msg;
    }
    
    /**
     * @param \Illuminate\Support\Collection $scores
     * @return string
     */
    public static function buildArenaLeaderboardMessage($scores)
    {
        $msg = '<strong> Arena Leaders </strong><br />';
        
        // Loop through them all
        $x = 1;
        foreach ($scores->all() as $item)
        {
            $msg .= '<strong>' . $x++ . '. ' . $item->account->gamertag . ':</strong> ' . number_format($item->score, 2) . '<br />';
        }
        
        return $msg;
    }
}
