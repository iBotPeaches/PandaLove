<?php

namespace Onyx\Halo5\Items;

/**
 * Class ArenaLeaderboardItem.
 */
class ArenaLeaderboardItem
{
    /**
     * @var float
     */
    public $score;

    /**
     * @var \Onyx\Account
     */
    public $account;

    public function __construct($score, $account)
    {
        $this->score = $score;
        $this->account = $account;
    }
}
