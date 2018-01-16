<?php

namespace Onyx\Fortnite;

use Onyx\Fortnite\Helpers\Network\Http;

/**
 * Class Client.
 */
class Client extends Http
{
    /**
     * @var array
     */
    private $account_cached = [];

    public function getAccountRoyaleStats(string $name, string $platform)
    {
        $this->getJson('test');
    }

    public function getAccountByTag(string $name, string $platform)
    {
        $this->getJson('test');
    }

    //---------------------------------------------------------------------------------
    // Private Functions
    //---------------------------------------------------------------------------------

}
