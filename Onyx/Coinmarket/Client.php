<?php

namespace Onyx\Coinmarket;

use Onyx\Coinmarket\Helpers\Network\Http;
use Onyx\Coinmarket\Helpers\String\Symbols;

class Client extends Http
{
    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    /**
     * @param string $name
     *
     * @return array
     */
    public function getTicker(string $name): array
    {
        $url = sprintf(Constants::$ticket, Symbols::getTickerId($name));

        try {
            return $this->getJson($url, 5)[0];
        } catch (\Exception $ex) {
            return [];
        }
    }
}
