<?php namespace Onyx\Destiny\Helpers\Network;

use GuzzleHttp\Client as Guzzle;

class Http {

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     *
     */
    public function __construct()
    {
        $this->guzzle = new Guzzle();
    }

    /**
     * Request an URL expecting JSON to be returned
     * @param $url
     * @return array
     * @throws BungieOfflineException
     */
    public function getJson($url)
    {
        $response = $this->guzzle->get($url);

        if ($response->getStatusCode() != 200)
        {
            throw new BungieOfflineException();
        }

        return $response->json();
    }
}

class BungieOfflineException extends \Exception {}

