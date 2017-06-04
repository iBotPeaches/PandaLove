<?php

namespace Onyx\Overwatch;

use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text as DestinyText;
use Onyx\Overwatch\Constants as OverwatchConstants;
use Onyx\Overwatch\Helpers\Network\Http;
use Onyx\Overwatch\Helpers\Network\OWApiNetworkException;
use Onyx\XboxLive\Enums\Console;

/**
 * Class Client
 * @package Onyx\Overwatch
 */
class Client extends Http
{
    /**
     * @var array
     */
    private $account_cached = [];

    /**
     * @param $account
     * @param $platform
     *
     * @throws OWApiNetworkException
     *
     * @return mixed
     */
    public function fetchBlobStat($account, $platform = Console::Xbox)
    {
        // xbl/pc/psn
        $url = sprintf(OverwatchConstants::$getBlobStats, $account->gamertag, Console::getOverwatchTag($platform));

        $data = $this->getJson($url);

        if (isset($data['any'])) {
            return $data['any'];
        }

        throw new OWApiNetworkException('Could not find account.');
    }

    public function getAccountByTag($account, $platform = 'xbl')
    {

    }

    /**
     * @param $account
     * @param int $platform
     * @return mixed
     */
    public function checkCacheForTag($account, $platform = Console::Xbox)
    {
        $seo = DestinyText::seoGamertag($account);

        if (isset($this->account_cached[$seo])) {
            return $this->account_cached[$seo];
        }

        $account = Account::where('seo', $seo)
            ->where('accountType', $platform)
            ->first();

        if ($account !== null) {
            $this->account_cached[$seo] = $account;

            return $account;
        }
    }
}
