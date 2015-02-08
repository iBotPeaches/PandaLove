<?php namespace Onyx\Destiny;

use Carbon\Carbon;
use GuzzleHttp;
use Intervention\Image\Facades\Image;
use Onyx\Account;
use Onyx\Destiny\Helpers\Assets\Images;
use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Character;

class Client extends Http {

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

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
     * @return array
     * @throws Helpers\Network\BungieOfflineException
     */
    public function fetchAccountData($account)
    {
        $url = sprintf(Constants::$platformDestiny, $account->accountType, $account->membershipId);

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
                $this->updateOrAddCharacter($url, $json['Response']['data']['characters'][$i]);
                $pair = "character_" . ($i + 1);
                $account->$pair = $json['Response']['data']['characters'][$i]['characterBase']['characterId'];
            }
        }

        $account->save();

        return $account;
    }

    /**
     * @param /Onyx/Account $account
     * @return array|null
     */
    public function getBungieProfile($account)
    {
        $url = sprintf(Constants::$getBungieAccount, $account->membershipId, $account->accountType);

        $json = $this->getJson($url);

        return isset($json['Response']['bungieNetUser']) ? $json['Response']['bungieNetUser'] : null;
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    /**
     * @param string $url
     * @param array $data
     */
    private function updateOrAddCharacter($url, $data)
    {
        $charBase = $data['characterBase'];

        $character = Character::where('characterId', $charBase['characterId'])->first();

        if ( ! $character instanceof Character)
        {
            $character = new Character();
            $character->membershipId = $charBase['membershipId'];
            $character->characterId = $charBase['characterId'];
        }

        $character->setTranslatorUrl($url);

        $character->last_played = new Carbon($charBase['dateLastPlayed']);
        $character->minutes_played = $charBase['minutesPlayedTotal'];
        $character->minutes_played_last_session = $charBase['minutesPlayedThisSession'];
        $character->level = $charBase['powerLevel'];
        $character->race = $charBase['raceHash'];
        $character->gender = $charBase['genderHash'];
        $character->class = $charBase['classHash'];
        $character->defense = $charBase['stats']['STAT_DEFENSE']['value'];
        $character->intellect = $charBase['stats']['STAT_INTELLECT']['value'];
        $character->discipline = $charBase['stats']['STAT_DISCIPLINE']['value'];
        $character->strength = $charBase['stats']['STAT_STRENGTH']['value'];
        $character->light = $charBase['stats']['STAT_LIGHT']['value'];

        $character->subclass = $charBase['peerView']['equipment'][0]['itemHash'];
        $character->helmet = $charBase['peerView']['equipment'][1]['itemHash'];
        $character->arms = $charBase['peerView']['equipment'][2]['itemHash'];
        $character->chest = $charBase['peerView']['equipment'][3]['itemHash'];
        $character->boots = $charBase['peerView']['equipment'][4]['itemHash'];
        $character->class_item = $charBase['peerView']['equipment'][5]['itemHash'];

        $character->primary = $charBase['peerView']['equipment'][6]['itemHash'];
        $character->secondary = $charBase['peerView']['equipment'][7]['itemHash'];
        $character->heavy = $charBase['peerView']['equipment'][8]['itemHash'];
        $character->ship = $charBase['peerView']['equipment'][9]['itemHash'];
        $character->sparrow = $charBase['peerView']['equipment'][10]['itemHash'];
        $character->ghost = $charBase['peerView']['equipment'][11]['itemHash'];
        $character->background = $charBase['peerView']['equipment'][12]['itemHash'];
        $character->shader = $charBase['peerView']['equipment'][13]['itemHash'];
        $character->emblem = $data['emblemHash'];
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