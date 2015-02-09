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
    private $items = null;

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
        if ($this->items == null)
        {
            $this->getItems();
        }

        $object = $this->items->filter(function($item) use ($hash)
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

    private function getItems()
    {
        $this->items = Cache::remember('hashes', 3600, function()
        {
            return Hash::all();
        });

        return $this->items;
    }

    private function updateItems()
    {
        Cache::forget('hashes');
        return $this->getItems();
    }
}

class HashNotLocatedException extends \Exception {};