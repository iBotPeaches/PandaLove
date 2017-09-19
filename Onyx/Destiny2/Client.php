<?php

namespace Onyx\Destiny2;

use Onyx\Account;
use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Helpers\String\Text;

class Client extends Http
{
    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    /**
     * @param $gamertag
     * @param int $platform
     * @return Account
     */
    public function getAccountByName($gamertag, $platform = 1)
    {
        // Switch PC to Blizzard
        $platform = ($platform == 3 ? 4 : $platform);

        /** @var Account $account */
        $account = $this->checkCacheForTag($gamertag, $platform);

        if ($account !== null) {
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

        $data = "foo";

        if ($account->gamertag !== $gamertag) {
            $account->gamertag = $gamertag;
            $account->save();
        }

        return $account;
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

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
