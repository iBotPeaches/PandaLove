<?php namespace Onyx\Destiny\Helpers\String;

use Illuminate\Support\Facades\Cache;
use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Objects\Hash;

class Hashes extends Http{

    /**
     * URL of request. To re-request if missing hashes
     *
     * @var string
     */
    private $url = null;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|static[]
     */
    private static $items = null;

    /**
     *
     * @var bool
     */
    private $allowedRetry = true;

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setUrl($url)
    {
        $this->url = $url;
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function map($hash, $title = true)
    {
        if (Hashes::$items == null)
        {
            $this->getItems();
        }

        $object = Hashes::$items->filter(function($item) use ($hash)
        {
            return $item->hash == $hash;
        })->first();

        if ($object instanceof Hash)
        {
            if ($title)
            {
                return $object->title;
            }

            return $object;
        }
        else
        {
            if ($this->allowedRetry)
            {
                $this->updateHashes();
                return $this->map($hash, $title);
            }
            else
            {
                throw new HashNotLocatedException();
            }
        }
    }

    /**
     * @param array $hashes
     */
    public static function setPremadeHashList($hashes)
    {
        Hashes::$items = Hash::whereIn('hash', $hashes)->get();
    }

    /**
     * @param $games
     */
    public static function cacheHistoryHashes($games)
    {
        $hashes = null;

        foreach($games as $game)
        {
            $hashes[] = $game->getOriginal('referenceId');
        }

        $hashes = self::removeEmptyAndDuplicates($hashes);
        self::setPremadeHashList($hashes);
    }

    /**
     * @param $games
     */
    public static function cacheTuesdayHashes($games)
    {
        $hashes = null;

        foreach($games as $game)
        {
            $hashes[] = $game->getOriginal('referenceId');

            foreach($game->players as $player)
            {
                $hashes[] = $player->getOriginal('emblem');
            }
        }

        $hashes = self::removeEmptyAndDuplicates($hashes);
        self::setPremadeHashList($hashes);
    }

    /**
     * @param $game
     */
    public static function cacheSingleGameHashes($game)
    {
        $hashes = null;

        $hashes[] = $game->getOriginal('referenceId');
        foreach($game->players as $player)
        {
            $hashes[] = $player->getOriginal('emblem');
        }

        $hashes = self::removeEmptyAndDuplicates($hashes);
        self::setPremadeHashList($hashes);
    }

    /**
     * @param $raids
     * @param $flawless
     * @param $tuesday
     * @param $pvp
     * @param $poe
     * @param $passages
     * @return array
     */
    public static function cacheGameHashes($raids, $flawless, $tuesday, $pvp, $poe, $passages)
    {
        $hashes = null;

        foreach([$raids, $flawless, $tuesday, $pvp, $poe, $passages] as $games)
        {
            foreach($games as $game)
            {
                $hashes[] = $game->getOriginal('referenceId');
            }
        }

        $hashes = self::removeEmptyAndDuplicates($hashes);
        self::setPremadeHashList($hashes);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $accounts
     * @return array
     */
    public static function cacheAccountsHashes($accounts)
    {
        $hashes = null;

        foreach($accounts as $account)
        {
            foreach($account->characters as $char)
            {
                $hashes[] = $char->getOriginal('race');
                $hashes[] = $char->getOriginal('gender');
                $hashes[] = $char->getOriginal('class');
                $hashes[] = $char->getOriginal('emblem');
            }
        }

        $hashes = self::removeEmptyAndDuplicates($hashes);
        self::setPremadeHashList($hashes);
    }

    /**
     * @param \Onyx\Account $account
     * @return array|null
     */
    public static function cacheAccountHashes($account)
    {
        $hashes = null;

        foreach($account->characters as $char)
        {
            foreach($char->getAllHashTitles() as $hash)
            {
                $hashes[] = $char->getOriginal($hash);
            }
        }

        $hashes = self::removeEmptyAndDuplicates($hashes);
        self::setPremadeHashList($hashes);
    }

    /**
     * @param boolean $andsign
     * @throws \Onyx\Destiny\Helpers\Network\BungieOfflineException
     */
    public function updateHashes($andsign = false)
    {
        if ($this->url == null)
        {
            $this->allowedRetry = false;
            $this->updateItems();
        }
        else
        {
            $json = $this->getJson($this->url . (($andsign) ? "&" : "?") . "definitions=true");
            Hash::loadHashesFromApi($json['Response']['definitions']);
            $this->allowedRetry = false;
            $this->updateItems();
        }
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    /**
     * @param $hashes
     * @return array
     */
    private static function removeEmptyAndDuplicates($hashes)
    {
        if ($hashes == null)
        {
            return;
        }

        return array_filter(array_unique($hashes));
    }

    private function getItems()
    {
        Hashes::$items = Hash::all();
        return Hashes::$items;
    }

    private function updateItems()
    {
        Cache::forget('hashes');
        return $this->getItems();
    }
}

class HashNotLocatedException extends \Exception {};