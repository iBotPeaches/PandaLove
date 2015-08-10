<?php namespace Onyx\XboxLive;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise as GuzzlePromise;
use Onyx\Destiny\Client as DestinyClient;
use Onyx\XboxLive\Helpers\Network\XboxAPI;
use Onyx\XboxLive\Constants as XboxConstants;

class Client extends XboxAPI {

    public function fetchAccountsPresence($accounts)
    {
        $client = new GuzzleClient([
            'base_uri' => XboxConstants::$getBaseXboxAPI
        ]);
        $destiny = new DestinyClient();

        // Set up getCommands
        $requests = array();
        foreach ($accounts as $account)
        {
            if ($account->xuid == null)
            {
                $account = $destiny->fetchAccountData($account);
            }

            $url = sprintf(XboxConstants::$getPresenceUrl, $account->xuid);
            $requests[$account->seo] = $client->getAsync($url, [
                'headers' => ['X-AUTH' => env('XBOXAPI_KEY')]
            ]);
        }

        $results = GuzzlePromise\Unwrap($requests);

        return $results;
    }
}