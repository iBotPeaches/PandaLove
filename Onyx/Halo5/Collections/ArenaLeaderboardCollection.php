<?php namespace Onyx\Halo5\Collections;

use Illuminate\Support\Collection;
use Onyx\Account;
use Onyx\Halo5\Items\ArenaLeaderboardItem;

/**
 * Class ArenaLeaderboardCollection
 * @package Onyx\Halo5\Collections
 * @property $items array
 */
class ArenaLeaderboardCollection extends Collection
{
    public function __construct($accounts)
    {
        /** @var $account Account */
        $items = [];
        foreach ($accounts as $account)
        {
            $score = log($account->h5->totalGames) * $account->h5->kd(false);

            // Mathematically improbable any will ever have the same score
            $items[] = new ArenaLeaderboardItem($score, $account);
        }

        usort($items, function($a, $b)
        {
            return strcmp($b->score, $a->score);
        });

        parent::__construct($items);
    }
}