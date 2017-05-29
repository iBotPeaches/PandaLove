<?php

namespace Onyx\Halo5\Collections;

use Illuminate\Support\Collection;
use Onyx\Account;
use Onyx\Halo5\Objects\PlaylistData;
use Onyx\Halo5\Objects\Season;

/**
 * Class SeasonCollection.
 *
 * @property array $items
 */
class SeasonCollection extends Collection
{
    /**
     * @var Season
     */
    public $season = null;

    public function __construct(Account $account, $items)
    {
        foreach ($items as $playlist) {
            /* @var $playlist PlaylistData */
            $this->items[$playlist->seasonId]['playlists'][$playlist->playlistId] = $playlist;
            $this->items[$playlist->seasonId]['season'] = $playlist->season;

            if ($this->season == null) {
                $this->season = $playlist->season;
            }

            if ($this->season instanceof Season && $this->season->start_date < $playlist->season->start_date) {
                $this->season = $playlist->season;
            }
        }

        usort($this->items, function ($a, $b) {
            return strtotime($b['season']->start_date) - strtotime($a['season']->start_date);
        });
    }

    /**
     * @return array
     */
    public function current()
    {
        if (count($this->items) == 0) {
            return false;
        }

        return $this->items[0]['playlists'];
    }
}
