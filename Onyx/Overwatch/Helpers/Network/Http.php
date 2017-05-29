<?php

namespace Onyx\Overwatch\Helpers\Network;

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

    public function getJson($url)
    {
        if (!$this->guzzle instanceof Guzzle) {
            $this->setupGuzzle();
        }

        $response = $this->guzzle->get($url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            throw new OWApiNetworkException();
        }

        return json_decode($response->getBody(), true);
    }
}

class OWApiNetworkException extends \Exception
{
}
