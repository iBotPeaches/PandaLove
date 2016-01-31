<?php namespace Onyx\Halo5\Collections;

use Illuminate\Support\Collection;
use Onyx\Account;
use Onyx\Halo5\Objects\PlaylistData;

class SeasonCollection extends Collection
{
    static $current;

    public function __construct(Account $account, $items)
    {
        foreach ($items as $playlist)
        {
            /** @var $playlist PlaylistData */
            $this->items[$playlist->seasonId]['playlists'][$playlist->playlistId] = $playlist;
            $this->items[$playlist->seasonId]['season'] = $playlist->season;

            if ($playlist->season->isActive)
            {
                self::$current = $playlist->season;
            }
        }
    }

    /**
     * @return array
     */
    public function current()
    {
        return $this->items[self::$current->contentId]['playlists'];
    }
}