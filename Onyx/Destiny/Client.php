<?php namespace Onyx\Destiny;

use Carbon\Carbon;
use GuzzleHttp;
use Onyx\Account;
use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Character;
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

        $account = $this->checkCacheForGamertag($gamertag);

        if ($account instanceof Account)
        {
            return $account;
        }

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
        for ($i = 0; $i <= 3; $i++)
        {
            if (isset($json['Response']['data']['characters'][$i]))
            {
                $this->updateOrAddCharacter($url, $account, $json['Response']['data']['characters'][$i]);

                $characterId = $json['Response']['data']['characters'][$i]['characterBase']['characterId'];
            }
        }

        $account->save();

        return $json;
    }

    /**
     * @param string $url
     * @param \Onyx\Account $account
     * @param array $data
     */
    private function updateOrAddCharacter($url, $account, $data)
    {
        $charBase = $data['characterBase'];

        $translator = new Hashes();
        $translator->setUrl($url);

        $character = Character::where('characterId', $charBase['characterId'])->first();

        if ( ! $character instanceof Character)
        {
            $character = new Character();
            $character->membershipId = $charBase['membershipId'];
            $character->characterId = $charBase['characterId'];
        }

        $character->last_played = new Carbon($charBase['dateLastPlayed']);
        $character->minutes_played = $charBase['minutesPlayedTotal'];
        $character->minutes_played_last_session = $charBase['minutesPlayedThisSession'];
        $character->level = $charBase['powerLevel'];
        $character->race = $translator->map($charBase['raceHash']);
        $character->gender = $translator->map($charBase['genderHash']);
        $character->class = $translator->map($charBase['classHash']);
        $character->defense = $charBase['stats']['STAT_DEFENSE']['value'];
        $character->save();
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

        return false;
    }
}

class PlayerNotFoundException extends \Exception {};