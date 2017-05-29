<?php

namespace Onyx\Hangouts\Network;

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
     * @param $sendto
     * @param $content
     *
     * @throws HangoutsServerOfflineException
     *
     * @return bool
     */
    public function postJson($url, $sendto, $content)
    {
        if (!$this->guzzle instanceof Guzzle) {
            $this->setupGuzzle();
        }

        $response = $this->guzzle->request('POST', $url, [
            'json' => [
                'key'     => env('BOT_APIKEY'),
                'sendto'  => $sendto,
                'content' => $content,
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            throw new HangoutsServerOfflineException();
        }

        return $response->getStatusCode() == 200;
    }
}

class HangoutsServerOfflineException extends \Exception
{
}
