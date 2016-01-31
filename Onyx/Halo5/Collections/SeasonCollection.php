<?php namespace Onyx\Halo5\Collections;

use Illuminate\Support\Collection;
use Onyx\Account;
use Onyx\Halo5\Objects\PlaylistData;

/**
 * Class SeasonCollection
 * @package Onyx\Halo5\Collections
 * @property array $items
 */
class SeasonCollection extends Collection
{
    public function __construct(Account $account, $items)
    {
        foreach ($items as $playlist)
        {
            /** @var $playlist PlaylistData */
            $this->items[$playlist->seasonId]['playlists'][$playlist->playlistId] = $playlist;
            $this->items[$playlist->seasonId]['season'] = $playlist->season;
        }

        usort($this->items, function($a, $b) {
            return strtotime($b['season']->start_date) - strtotime($a['season']->start_date);
        });
    }

    /**
     * @return array
     */
    public function current()
    {
        return $this->items[0]['playlists'];
    }
}