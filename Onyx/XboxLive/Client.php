<?php namespace Onyx\XboxLive;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Promise as GuzzlePromise;
use Onyx\Account;
use Onyx\Destiny\Client as DestinyClient;
use Onyx\XboxLive\Helpers\Network\XboxAPI;
use Onyx\XboxLive\Constants as XboxConstants;
use Onyx\XboxLive\Helpers\Network\XboxAPIErrorException;

class Client extends XboxAPI {

    /**
     * @var array
     */
    public $acceptedGameIds = [
        '972249091',            // GTA5
        '247546985',            // Destiny
        '1144039928',           // MCC
        '1292135256',           // Titanfall
        '219630713',            // Halo 5
        '74304278'              // Division
    ];

    /**
     * @param $accounts Account[]
     * @return mixed
     */
    public function fetchAccountsPresence($accounts)
    {
        $client = new GuzzleClient([
            'base_uri' => XboxConstants::$getBaseXboxAPI
        ]);

        // Set up getCommands
        $requests = array();
        foreach ($accounts as $account)
        {
            if ($account->xuid == null)
            {
                if ($this->getXuid($account) == null)
                {
                    continue;
                }
            }

            $url = sprintf(XboxConstants::$getPresenceUrl, $account->xuid);
            $requests[$account->seo] = $client->getAsync($url, [
                'headers' => ['X-AUTH' => env('XBOXAPI_KEY')]
            ]);
        }

        $results = GuzzlePromise\Unwrap($requests);

        return $results;
    }

    /**
     * @param $account Account
     * @return mixed
     * @throws XboxAPIErrorException
     */
    public function fetchAccountBio($account)
    {
        $url = sprintf(Constants::$getGamercard, $account->gamertag);

        $data = $this->getJson($url);

        if (isset($data['gamertag']))
        {
            return $data['bio'];
        }
        else
        {
            throw new XboxAPIErrorException('Gamertag was not found.');
        }
    }

    /**
     * @param $presence
     * @param $accounts Account[]
     * @return string
     */
    public function prettifyOnlineStatus($presence, $accounts)
    {
        $key = 'online_status';

        if (Cache::has($key))
        {
            return Cache::get($key);
        }
        else
        {
            $user_string = '<strong>Online Status</strong><br/>';
            $found = false;

            foreach ($presence as $seo => $response)
            {
                $data = json_decode($response->getBody(), true);

                if (isset($data['state']) && $data['state'] == "Online")
                {
                    foreach ($data['devices'] as $device)
                    {
                        if ($device['type'] == "XboxOne")
                        {
                            foreach ($device['titles'] as $title)
                            {
                                if (in_array($title['id'], $this->acceptedGameIds))
                                {
                                    $found = true;
                                    $gt = $accounts->where('seo', $seo)->first();
                                    $user_string .= "<strong>" . $gt->gamertag . ": </strong>" . $title['name'];
                                    if (isset($title['activity']))
                                    {
                                        $user_string .= " (" . $title['activity']['richPresence'] . ")";
                                    }
                                    $user_string .= "<br/>";
                                }
                            }
                        }
                    }
                }
            }

            if (! $found)
            {
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
     * @return mixed|null
     */
    private function getXuid(&$account)
    {
        if ($account->xuid == null)
        {
            $url = sprintf(Constants::$getGamertagXUID, $account->gamertag);
            $xuid = $this->getJson($url, true);

            if ($xuid != null)
            {
                $account->xuid = $xuid;
                $account->save();

                return $xuid;
            }
        }

        return null;
    }
}