<?php namespace Onyx\Halo5\Items;

/**
 * Class ArenaLeaderboardItem
 * @package Onyx\Halo5\Items
 */
class ArenaLeaderboardItem {

    /**
     * @var $score float
     */
    public $score;

    /**
     * @var $account \Onyx\Account
     */
    public $account;

    public function __construct($score, $account)
    {
        $this->score = $score;
        $this->account = $account;
    }
}