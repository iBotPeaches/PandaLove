<?php

namespace Onyx\Destiny2\Helpers\Network;

use Barryvdh\Debugbar\Facade as DebugBar;
use GuzzleHttp\Client as Guzzle;

class Http
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    public function __construct()
    {
        $this->setupGuzzle();
    }

    private function setupGuzzle()
    {
        $this->guzzle = new Guzzle();
    }

    /**
     * Request an URL expecting JSON to be returned.
     *
     * @param $url
     * @param $cache integer
     *
     * @throws Bungie2OfflineException
     *
     * @return array
     */
    public function getJson($url, $cache = 0)
    {
        if (!$this->guzzle instanceof Guzzle) {
            $this->setupGuzzle();
        }

        $sum = md5($url);

        if ($cache != 0 && \Cache::has($sum)) {
            return \Cache::get($sum);
        }

        DebugBar::startMeasure($sum, $url);

        $response = $this->guzzle->get($url, [
            'headers' => ['X-API-Key' => env('BUNGIE_KEY')],
        ]);

        DebugBar::stopMeasure($sum);

        if ($response->getStatusCode() != 200) {
            throw new Bungie2OfflineException();
        }

        if ($cache != 0) {
            \Cache::put($sum, json_decode($response->getBody(), true), $cache);
        }

        return json_decode($response->getBody(), true);
    }
}

class Bungie2OfflineException extends \Exception
{
}
