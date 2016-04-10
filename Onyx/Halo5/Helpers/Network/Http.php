<?php namespace Onyx\Halo5\Helpers\Network;

use Barryvdh\Debugbar\Facade as DebugBar;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ServerException;
use Intervention\Image\Facades\Image;

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
     * @param $cache integer
     * @return array
     * @throws ThreeFourThreeOfflineException
     */
    public function getJson($url, $cache = 0)
    {
        if (! $this->guzzle instanceof Guzzle)
        {
            $this->setupGuzzle();
        }

        $sum = md5($url);

        if ($cache != 0 && \Cache::has($sum))
        {
            return \Cache::get($sum);
        }

        DebugBar::startMeasure($sum, $url);

        $response = $this->guzzle->get($url, [
            'headers' => ['Ocp-Apim-Subscription-Key' => env('HALO5_KEY')]
        ]);

        DebugBar::stopMeasure($sum);

        if ($response->getStatusCode() != 200)
        {
            throw new ThreeFourThreeOfflineException();
        }

        if ($cache != 0)
        {
            \Cache::put($sum, json_decode($response->getBody(), true), $cache);
        }

        return json_decode($response->getBody(), true, 512, JSON_BIGINT_AS_STRING);
    }

    /**
     * @param $url
     * @return \Intervention\Image\Image
     * @throws ThreeFourThreeOfflineException
     */
    public function getAsset($url)
    {
        if (! $this->guzzle instanceof Guzzle)
        {
            $this->setupGuzzle();
        }

        try
        {
            $response = $this->guzzle->get($url, [
                'headers' => ['Ocp-Apim-Subscription-Key' => env('HALO5_KEY')]
            ]);
        }
        catch (ServerException $e)
        {
            return null;
        }

        if ($response->getStatusCode() != 200)
        {
            throw new ThreeFourThreeOfflineException();
        }

        return Image::make($response->getBody()->getContents());
    }
}

class ThreeFourThreeOfflineException extends \Exception {}

