<?php namespace Onyx\Destiny;

use GuzzleHttp;
use Onyx\Account;
use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Helpers\String\Text;

class Client extends Http {

    /**
     * @param $platform
     * @param $gamertag
     * @return \Onyx\Account
     * @throws Helpers\Network\BungieOfflineException
     * @throws PlayerNotFoundException
     */
    public function fetchAccountByGamertag($platform, $gamertag)
    {
        $platform = intval($platform);
        $url = sprintf(Constants::$searchDestinyPlayer, $platform, $gamertag);

        $this->checkCacheForGamertag($gamertag);

        $json = $this->getJson($url);

        if (isset($json['Response'][0]['membershipId']))
        {
            return Account::firstOrCreate([
                'membershipId' => $json['Response'][0]['membershipId'],
                'gamertag' => $json['Response'][0]['displayName'],
                'accountType' => $json['Response'][0]['membershipType']
            ]);
        }
        else
        {
            throw new PlayerNotFoundException();
        }
    }

    /**
     * @param $gamertag
     * @return \Onyx\Account|void
     */
    private function checkCacheForGamertag($gamertag)
    {
        $account = Account::where('seo', Text::seoGamertag($gamertag))->first();

        if ($account instanceof Account)
        {
            return $account;
        }
    }
}

class PlayerNotFoundException extends \Exception {};