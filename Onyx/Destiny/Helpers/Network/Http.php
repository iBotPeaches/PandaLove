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
        $this->setupGuzzle();
    }

    private function setupGuzzle()
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
        if (! $this->guzzle instanceof Guzzle)
        {
            $this->setupGuzzle();
        }

        $response = $this->guzzle->get($url, [
            'headers' => ['X-API-Key' => env('BUNGIE_KEY')]
        ]);

        if ($response->getStatusCode() != 200)
        {
            throw new BungieOfflineException();
        }

        return json_decode($response->getBody(), true);
    }
}

class BungieOfflineException extends \Exception {}

