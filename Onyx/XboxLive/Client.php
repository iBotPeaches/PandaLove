<?php

namespace Onyx\XboxLive;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Onyx\Account;
use Onyx\XboxLive\Constants as XboxConstants;
use Onyx\XboxLive\Helpers\Network\XboxAPI;
use Onyx\XboxLive\Helpers\Network\XboxAPIErrorException;

class Client extends XboxAPI
{
    /**
     * @var array
     */
    public $acceptedGameIds = [
        '972249091',            // GTA5
        '247546985',            // Destiny
        '1144039928',           // MCC
        '1292135256',           // Titanfall
        '219630713',            // Halo 5
        '74304278',             // Division
        '934424724',            // Gears of War 4 - BETA
        '706211867',            // Overwatch - BETA
        '94618376',             // Overwatch
        '552499398',            // Gears of War 4
        '1204533074',           // Borderlands 2
        '1145574011',           // Titanfall 2
        '1386529057',           // Battlefield 1
        '144389848',            // Destiny 2 - Base Game
        '545844082',            // Call of Duty - WWII
        '267695549',            // Fortnite Battle Royale
    ];

    /**
     * @param $accounts Account[]
     *
     * @return mixed
     */
    public function fetchAccountsPresence($accounts)
    {
        $client = new GuzzleClient([
            'base_uri' => XboxConstants::$getBaseXboxAPI,
        ]);

        // Set up getCommands
        $results = [];

        foreach ($accounts as $account) {
            if ($account->xuid == null) {
                if ($this->getXuid($account) == null) {
                    continue;
                }
            }

            $url = sprintf(XboxConstants::$getPresenceUrl, $account->xuid);

            $results[$account->seo] = $client->get($url, [
                'headers' => ['X-AUTH' => env('XBOXAPI_KEY')],
            ]);
        }

        return $results;
    }

    /**
     * @param $account Account
     *
     * @throws XboxAPIErrorException
     *
     * @return mixed
     */
    public function fetchAccountBio($account)
    {
        $url = sprintf(Constants::$getGamercard, $account->gamertag);

        $data = $this->getJson($url);

        if (isset($data['gamertag'])) {
            return $data['bio'];
        } else {
            throw new XboxAPIErrorException('Gamertag was not found.');
        }
    }

    /**
     * @param Response[] $presence
     * @param $accounts Account[]
     *
     * @return string
     */
    public function prettifyOnlineStatus($presence, $accounts)
    {
        $key = 'online_status';

        if (Cache::has($key)) {
            return Cache::get($key);
        } else {
            $user_string = '<strong>Online Status</strong><br/>';
            $found = false;

            foreach ($presence as $seo => $response) {
                if ($response->getReasonPhrase() !== 'OK') {
                    continue;
                }
                $data = json_decode($response->getBody()->getContents(), true);

                if (isset($data['state']) && $data['state'] == 'Online') {
                    foreach ($data['devices'] as $device) {
                        if ($device['type'] == 'XboxOne') {
                            foreach ($device['titles'] as $title) {
                                if (in_array($title['id'], $this->acceptedGameIds)) {
                                    $found = true;
                                    $gt = $accounts->where('seo', $seo)->first();
                                    $user_string .= '<strong>'.$gt->gamertag.': </strong>'.$title['name'];
                                    if (isset($title['activity']) && isset($title['activity']['richPresence'])) {
                                        $user_string .= ' ('.$title['activity']['richPresence'].')';
                                    }
                                    $user_string .= '<br/>';
                                }
                            }
                        }
                    }
                }
            }

            if (!$found) {
                $user_string = 'No-one is online. Pity us.';
            }
            Cache::put($key, $user_string, 5);

            return $user_string;
        }
    }

    //-----------------------------------------------------------------
    // Private Functions
    //-----------------------------------------------------------------

    /**
     * @param $account Account
     *
     * @return mixed|null
     */
    private function getXuid(&$account)
    {
        if ($account->xuid == null) {
            $url = sprintf(Constants::$getGamertagXUID, $account->gamertag);
            $xuid = $this->getJson($url, true);

            if ($xuid != null) {
                $account->xuid = $xuid;
                $account->save();

                return $xuid;
            }
        }
    }
}
