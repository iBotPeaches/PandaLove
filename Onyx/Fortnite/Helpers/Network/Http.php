<?php

namespace Onyx\Fortnite\Helpers\Network;

use GuzzleHttp\Client as Guzzle;
use Onyx\Fortnite\Constants;

class Http
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * @var array
     */
    protected $config;

    public function __construct()
    {
        $this->setupGuzzle();
        $this->config = config('services.fortnite');
    }

    private function setupGuzzle()
    {
        $this->guzzle = new Guzzle();
    }

    public function getJson($url)
    {
        $this->oAuthLogin();
        if (!$this->guzzle instanceof Guzzle) {
            $this->setupGuzzle();
        }

        $response = $this->guzzle->get($url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            throw new FortniteApiNetworkException();
        }

        return json_decode($response->getBody(), true);
    }

    private function oAuthLogin()
    {
        $payload = [
            'grant_type' => 'password',
            'username' => $this->config['email'],
            'password' => $this->config['password'],
            'includePerms' => true
        ];

        if (! $this->guzzle instanceof Guzzle) {
            $this->setupGuzzle();
        }

        $response = $this->guzzle->post(Constants::$oAuthToken, [
            'headers' => [
                'Authorization' => 'Basic ' . $this->config['launcher'],
                'Accept' => 'application/json',
            ],
            'form_params' => $payload
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new FortniteApiNetworkException();
        }

        $tokens = json_decode($response->getBody(), true);

        if (isset($tokens['access_token'])) {
            $this->oAuthExchange($tokens['access_token']);
        }
    }

    private function oAuthExchange(string $accessToken): void
    {
        $response = $this->guzzle->get(Constants::$oAuthExchange, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json'
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new FortniteApiNetworkException();
        }

        $data = json_decode($response->getBody(), true);

        if (isset($data['code'])) {
            $this->oAuthEglToken($data['code']);
        }
    }

    private function oAuthEglToken(string $exchangeCode): void
    {
        $payload = [
            'grant_type' => 'exchange_code',
            'exchange_code' => $exchangeCode,
            'includePerms' => true,
            'token_type' => 'egl'
        ];

        $response = $this->guzzle->post(Constants::$oAuthToken, [
            'headers' => [
                'Authorization' => 'Basic ' . $this->config['client'],
                'Accept' => 'application/json',
            ],
            'form_params' => $payload
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new FortniteApiNetworkException();
        }

        $data = json_decode($response->getBody(), true);
        dd($data);
        // TODO
    }
}

class FortniteApiNetworkException extends \Exception
{
}
