<?php namespace Onyx\XboxLive\Helpers\Network;

use GuzzleHttp\Client as Guzzle;

class XboxAPI {

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

    public function getJson($url, $xuid = false)
    {
        if (! $this->guzzle instanceof Guzzle)
        {
            $this->setupGuzzle();
        }

        $response = $this->guzzle->get($url, [
            'headers' => [
                'X-AUTH' => env('XBOXAPI_KEY'),
                'Accept' => 'application/json'
            ]
        ]);

        if ($response->getStatusCode() != 200)
        {
            throw new XboxAPIErrorException();
        }

        // Don't let it auto cast
        if ($xuid)
        {
            return $response->getBody(true);
        }
        else
        {
            return $response->json();
        }
    }
}

class XboxAPIErrorException extends \Exception {}