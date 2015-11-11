<?php namespace Onyx\Halo5\Helpers\Network;

use GuzzleHttp\Client as Guzzle;
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
     * @return array
     * @throws ThreeFourThreeOfflineException
     */
    public function getJson($url)
    {
        if (! $this->guzzle instanceof Guzzle)
        {
            $this->setupGuzzle();
        }

        $response = $this->guzzle->get($url, [
            'headers' => ['Ocp-Apim-Subscription-Key' => env('HALO5_KEY')]
        ]);

        if ($response->getStatusCode() != 200)
        {
            throw new ThreeFourThreeOfflineException();
        }

        return json_decode($response->getBody(), true);
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

        $response = $this->guzzle->get($url, [
            'headers' => ['Ocp-Apim-Subscription-Key' => env('HALO5_KEY')]
        ]);

        if ($response->getStatusCode() != 200)
        {
            throw new ThreeFourThreeOfflineException();
        }

        return Image::make($response->getBody()->getContents());
    }
}

class ThreeFourThreeOfflineException extends \Exception {}

