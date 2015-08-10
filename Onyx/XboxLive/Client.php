<?php namespace Onyx\XboxLive;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use Onyx\Destiny\Client as DestinyClient;
use Onyx\XboxLive\Helpers\Network\XboxAPI;
use Onyx\XboxLive\Constants as XboxConstants;

class Client extends XboxAPI {

    public function fetchAccountsPresence($accounts)
    {
        $client = new GuzzleClient([
            'base_url' => XboxConstants::$getBaseXboxAPI
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
            $requests[] = $client->get($url, ['headers' => ['X-AUTH' => env('XBOXAPI_KEY')]]);
        }

        $commands = $client->send($requests);

        return $commands;
    }
}