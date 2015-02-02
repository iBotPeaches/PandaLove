<?php namespace Onyx\Destiny\Helpers\String;

use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Objects\Hash;

class Hashes extends Http{

    /**
     * URL of request. To re-request if missing hashes
     *
     * @var string
     */
    private $url = '';

    /**
     * @var \Illuminate\Database\Eloquent\Collection|static[]
     */
    private $items;

    /**
     *
     * @var bool
     */
    private $allowedRetry = true;

    function __construct()
    {
        $this->items = Hash::all();
    }

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

    public function map($hash, $title = false)
    {
        if (array_key_exists($hash, $this->items))
        {
            if ($title)
            {
                return $this->items['hash']->title;
            }
            return $this->items['hash'];
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

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    /**
     * @throws \Onyx\Destiny\Helpers\Network\BungieOfflineException
     */
    private function updateHashes()
    {
        $json = $this->getJson($this->url);
        Hash::loadHashesFromApi($json['Response']['definitions']);
        $this->allowedRetry = false;
    }
}

class HashNotLocatedException extends \Exception {};