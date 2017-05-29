<?php

namespace Onyx\Overwatch;

use Onyx\Overwatch\Constants as OverwatchConstants;
use Onyx\Overwatch\Helpers\Network\Http;
use Onyx\Overwatch\Helpers\Network\OWApiNetworkException;

class Client extends Http
{
    /**
     * @param $account
     *
     * @throws OWApiNetworkException
     *
     * @return mixed
     */
    public function fetchBlobStat($account)
    {
        // xbl/pc/psn
        $url = sprintf(OverwatchConstants::$getBlobStats, $account->gamertag, 'xbl');

        $data = $this->getJson($url);

        if (isset($data['any'])) {
            return $data['any'];
        }

        throw new OWApiNetworkException('Could not find account.');
    }
}
