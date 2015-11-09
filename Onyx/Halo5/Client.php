<?php namespace Onyx\Halo5;

use Onyx\Halo5\Helpers\Network\Http;

class Client extends Http {

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function getMedals()
    {
        $url = Constants::$metadata_medals;

        return $this->getJson($url);
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------
}
