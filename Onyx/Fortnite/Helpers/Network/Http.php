<?php

namespace Onyx\Fortnite\Helpers\Network;

use Carbon\Carbon;
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

    protected $accessKeyCacheKey = 'fortniteAccessKey';
    protected $refreshKeyCacheKey = 'fortniteRefreshKey';

    protected $accessToken = null;
    protected $refreshToken = null;

    public function __construct()
    {
        $this->setupGuzzle();
        $this->config = config('services.fortnite');
    }

    private function setupGuzzle()
    {
        $this->guzzle = new Guzzle();
    }

    public function getJson($url, $minutes = 5): ?array
    {
        $key = md5($url);

        $this->determineoAuthStatus();

        if (!$this->guzzle instanceof Guzzle) {
            $this->setupGuzzle();
        }

        if (\Cache::has($key)) {
            return \Cache::get($key);
        }

        try {
            $response = $this->guzzle->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() != 200) {
                throw new FortniteApiNetworkException();
            }

            $data = \GuzzleHttp\json_decode($response->getBody(), true);

            if ($minutes > 0) {
                \Cache::put($key, $data, $minutes);
            }
            return $data;
        } catch (\Exception $ex) {
            return null;
        }
    }

    private function determineoAuthStatus(): void
    {
        // 1) We have a valid access token still
        if (\Cache::has($this->accessKeyCacheKey)) {
            $this->accessToken = \Cache::get($this->accessKeyCacheKey);
            return;
        }

        // 2) We have a valid refresh token
        if (\Cache::has($this->refreshKeyCacheKey)) {
            $this->oAuthRefresh(\Cache::get($this->refreshKeyCacheKey));
            return;
        }

        $this->oAuthLogin();
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

    private function oAuthEglToken(string $exchangeCode): array
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
        $this->parseoAuthIntoCache($data);
        return $data;
    }

    private function oAuthRefresh(string $refreshToken): array
    {
        $payload = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'includePerms' => true
        ];

        $response = $this->guzzle->post(Constants::$oAuthToken, [
            'headers' => [
                'Authorization' => 'Basic ' . $this->config['client'],
                'Accept' => 'application/json',
            ],
            'form_params' => $payload,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new FortniteApiNetworkException();
        }

        $data = json_decode($response->getBody(), true);
        $this->parseoAuthIntoCache($data);
        return $data;
    }

    /**
     * @param array $data
     * @throws FortniteApiNetworkException
     */
    private function parseoAuthIntoCache(array $data): void
    {
        if (isset($data['access_token']) && isset($data['refresh_token'])) {
            \Cache::put($this->accessKeyCacheKey, $data['access_token'], Carbon::parse($data['expires_at'], 'Z'));
            \Cache::put($this->refreshKeyCacheKey, $data['refresh_token'], Carbon::parse($data['refresh_expires_at'], 'Z'));

            $this->accessToken = $data['access_token'];
            $this->refreshToken = $data['refresh_token'];
        } else {
            throw new FortniteApiNetworkException();
        }
    }
}

class FortniteApiNetworkException extends \Exception
{
}
