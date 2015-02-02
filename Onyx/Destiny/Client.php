<?php namespace Onyx\Destiny;

use GuzzleHttp;
use Onyx\Account;
use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Hash;

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
     * @param /Onyx/Account $account
     * @param int $platform
     * @param string $membershipId
     * @return array
     * @throws Helpers\Network\BungieOfflineException
     */
    public function fetchAccountData($account, $platform, $membershipId)
    {
        $platform = intval($platform);
        $url = sprintf(Constants::$platformDestiny, $platform, $membershipId);

        $json = $this->getJson($url);

        $account->clanName = $json['Response']['data']['clanName'];
        $account->clanTag = $json['Response']['data']['clanTag'];
        $account->glimmer = $json['Response']['data']['inventory']['currencies'][0]['value'];
        $account->grimoire = $json['Response']['data']['grimoireScore'];

        // characters
        $account->character_1 = isset($json['Response']['data']['characters'][0])
            ? $json['Response']['data']['characters'][0]['characterBase']['characterId']
            : null;

        $account->character_2 = isset($json['Response']['data']['characters'][1])
            ? $json['Response']['data']['characters'][1]['characterBase']['characterId']
            : null;

        $account->character_3 = isset($json['Response']['data']['characters'][2])
            ? $json['Response']['data']['characters'][2]['characterBase']['characterId']
            : null;

        $account->save();

        return $json;
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