<?php

namespace Onyx\Destiny2;

use Onyx\Account;
use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny2\Helpers\Network\Bungie2OfflineException;
use Onyx\Destiny2\Objects\Character;
use Onyx\Destiny2\Objects\Data;

class Client extends Http
{
    /**
     * @var array
     */
    protected static $instances = [];

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    /**
     * @param $type
     * @param $hash
     * @return mixed
     */
    public function getHash($type, $hash)
    {
        $instance = array_get(static::$instances, "$type.$hash");

        if (! $instance) {
            $storage = storage_path('d2');

            if (!\File::exists($storage)) {
                \File::makeDirectory($storage, 0775, true);
            }

            if (!\File::exists($storage.'/'.$type)) {
                \File::makeDirectory($storage.'/'.$type, 0775, true);
            }

            $file = $storage . '/' . $type . '/' . $hash . '.php';
            if (\File::exists($file)) {
                return include $file;
            }

            $data = $this->getEntity($type, $hash);
            \File::put($file, '<?php return '.var_export($data, true).";\n");
            return $data;
        }

        return $instance;
    }

    /**
     * @param $gamertag
     * @param int $platform
     * @return Account
     * @throws Bungie2OfflineException
     */
    public function getAccountByName($gamertag, $platform = 1)
    {
        // Switch PC to Blizzard
        $platform = ($platform == 3 ? 4 : $platform);

        /** @var Account $account */
        $account = $this->checkCacheForTag($gamertag, $platform);

        if ($account !== null && $account->destiny2 !== null) {
            return $account;
        }

        if ($account === null) {
            $account = Account::firstOrCreate([
                'gamertag' => $gamertag,
                'accountType' => $platform
            ]);
        } else {
            $account->gamertag = $gamertag;
        }

        $results = $this->searchDestinyPlayer($gamertag, $platform);

        $membershipId = null;
        foreach($results as $result) {
            if ($result['membershipType'] == $platform) {
                $membershipId = $result['membershipId'];
            }
        }

        if (empty($membershipId)) {
            throw new Bungie2OfflineException('Account was not found.');
        }

        $data = $this->getProfile($account->accountType, $membershipId);
        foreach ($data['characters']['data'] as $characterId => $character) {
            $this->updateOrCreateCharacter($character);
        }

        $this->updateOrCreateData($account, $data);

        if ($account->gamertag !== $gamertag) {
            $account->gamertag = $gamertag;
            $account->save();
        }

        return $account;
    }

    /**
     * @param Account $account
     * @return Account
     */
    public function updateAccount(Account $account)
    {
        $data = $this->getProfile($account->accountType, $account->destiny2->membershipId);
        foreach ($data['characters']['data'] as $characterId => $character) {
            $this->updateOrCreateCharacter($character);
        }

        $this->updateOrCreateData($account, $data);

        return $account;
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    private function updateOrCreateCharacter(array $character)
    {
        return Character::updateOrCreate([
            'characterId' => $character['characterId'],
        ], [
            'characterId' => $character['characterId'],
            'lastPlayed' => $character['dateLastPlayed'],
            'minutesPlayedTotal' => $character['minutesPlayedTotal'],
            'light' => $character['light'],
            'raceHash' => $character['raceHash'],
            'genderHash' => $character['genderHash'],
            'classHash' => $character['classHash'],
            'emblemPath' => $character['emblemPath'],
            'backgroundPath' => $character['emblemBackgroundPath'],
            'emblemHash' => $character['emblemHash'],
            'level' => $character['baseCharacterLevel']
        ]);
    }

    /**
     * @param Account $account
     * @param array $data
     * @return mixed
     */
    private function updateOrCreateData(Account $account, array $data)
    {
        $characterIds = array_get($data, 'profile.data.characterIds');
        $membershipId = array_get($data, 'profile.data.userInfo.membershipId'
        );
        $character_1 = $characterIds[0] ?? null;
        $character_2 = $characterIds[1] ?? null;
        $character_3 = $characterIds[2] ?? null;

        return Data::updateOrCreate([
            'account_id' => $account->id
        ], [
            'account_id' => $account->id,
            'membershipId' => $membershipId,
            'character_1' => $character_1,
            'character_2' => $character_2,
            'character_3' => $character_3
        ]);
    }

    /**
     * @param $membershipType
     * @param $membershipId
     * @return mixed
     * @throws Bungie2OfflineException
     */
    private function getProfile($membershipType, $membershipId)
    {
        $url = sprintf(Constants::$platformDestiny, $membershipType, $membershipId);

        $result = $this->getJson($url, 5);

        if (isset($result['Response'])) {
            return $result['Response'];
        }

        throw new Bungie2OfflineException('Could not load Destiny 2 Profile.');
    }

    /**
     * @param $gamertag
     * @param $platform
     * @return mixed
     * @throws Bungie2OfflineException
     */
    private function searchDestinyPlayer($gamertag, $platform)
    {
        $platform = 'all';
        $url = sprintf(Constants::$searchDestinyPlayer, $gamertag, $platform);

        $result = $this->getJson($url, 5);

        if (isset($result['Response'])) {
            return $result['Response'];
        }

        throw new Bungie2OfflineException('Could not locate Destiny 2 Profile.');
    }

    /**
     * @param $type
     * @param $hash
     * @return mixed
     * @throws Bungie2OfflineException
     */
    private function getEntity($type, $hash)
    {
        $url = sprintf(Constants::$entityDefinition, $type, $hash);

        $result = $this->getJson($url);

        if (isset($result['Response'])) {
            return $result['Response'];
        }

        throw new Bungie2OfflineException('Could not find this hash');
    }

    /**
     * @param $gamertag
     * @param $platform
     * @return Account
     */
    private function checkCacheForTag($gamertag, $platform)
    {
        $seo = Text::seoGamertag($gamertag);

        /** @var Account $account */
        $account = Account::where('seo', $seo)
            ->where('accountType', $platform)
            ->first();

        return $account;
    }

}

class PlayerNotFoundException extends \Exception
{
}
class GameNotFoundException extends \Exception
{
}
