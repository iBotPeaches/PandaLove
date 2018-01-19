<?php

namespace Onyx\Laravel;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;
use Onyx\Account;
use Onyx\Calendar\Objects\Event as GameEvent;
use Onyx\Destiny\Client as DestinyClient;
use Onyx\Destiny\GameNotFoundException;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Character;
use Onyx\Destiny\Objects\Game;
use Onyx\Destiny\PlayerNotFoundException;
use Onyx\Destiny2\Client as Destiny2Client;
use Onyx\Destiny2\Helpers\Network\Bungie2OfflineException;
use Onyx\Fortnite\Client as FortniteClient;
use Onyx\Fortnite\Helpers\Network\FortniteApiNetworkException;
use Onyx\Halo5\Client as Halo5Client;
use Onyx\Halo5\H5PlayerNotFoundException;
use Onyx\Overwatch\Client as OverwatchClient;
use Onyx\Overwatch\Helpers\Network\OWApiNetworkException;
use Onyx\User;
use Onyx\XboxLive\Client as XboxClient;

class CustomValidator extends Validator
{
    public function validateGameReal($attribute, $value, $parameters)
    {
        $client = new DestinyClient();

        try {
            $game = $client->fetchGameByInstanceId($value);
        } catch (GameNotFoundException $e) {
            return false;
        }

        return true;
    }

    public function validateGameExistsReal($attribute, $value, $parameters)
    {
        try {
            $game = Game::where('instanceId', $value)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return true;
    }

    public function validateGamertagReal($attribute, $value, $parameters)
    {
        $client = new DestinyClient();

        try {
            $account = $client->fetchAccountByGamertag(1, $value);
        } catch (PlayerNotFoundException $e) {
            return false;
        }

        return true;
    }

    public function validateDestinyTagExists($attribute, $value, $parameters)
    {
        $client = new DestinyClient();

        try {
            $account = $client->searchAccountByName($value);
        } catch (PlayerNotFoundException $ex) {
            return false;
        }

        return true;
    }

    public function validateDestiny2TagExists($attribute, $value, $parameters)
    {
        $client = new Destiny2Client();

        try {
            $account = $client->getAccountByName($value, $this->data['platform']);
        } catch (Bungie2OfflineException $ex) {
            return false;
        }

        return true;
    }

    public function validateH5GamertagReal($attribute, $value, $parameters)
    {
        $client = new Halo5Client();

        try {
            $account = $client->getAccountByGamertag($value);
        } catch (H5PlayerNotFoundException $e) {
            return false;
        } catch (ClientException $e) {
            return false;
        }

        return true;
    }

    public function validateOverwatchReal($attribute, $value, $parameters)
    {
        $client = new OverwatchClient();

        try {
            $account = $client->getAccountByTag($value, $this->data['platform']);
        } catch (OWApiNetworkException $ex) {
            return false;
        }

        return true;
    }

    public function validateFortniteReal($attribute, $value, $parameters)
    {
        $client = new FortniteClient();

        try {
            $account = $client->getAccountByTag($value, $this->data['platform']);
        } catch (FortniteApiNetworkException $ex) {
            return false;
        }

        return true;
    }

    public function validateCharacterReal($attribute, $value, $parameters)
    {
        try {
            $character = Character::where('characterId', $value)->firstOrFail();
        } catch (ModelNotFoundException $e) {

            // lets try for a D2 one now
            try {
                $character = \Onyx\Destiny2\Objects\Character::where('characterId', $value)->firstOrFail();

                return true;
            } catch (ModelNotFoundException $ex) {
                return false;
            }
        }

        return true;
    }

    public function validateEventExists($attribute, $value, $parameters)
    {
        try {
            $event = GameEvent::where('id', $value)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return true;
    }

    public function validateGamertagExists($attribute, $value, $parameters)
    {
        $account = $this->getAccount($value);

        if ($account instanceof Account && $account->user == null) {
            return true;
        }

        return false;
    }

    public function validateMottoContains($attribute, $value, $parameters)
    {
        $account = $this->getAccount($value);
        $user = \Auth::user();

        if ($account instanceof Account && $user instanceof User) {
            $client = new XboxClient();
            $bio = $client->fetchAccountBio($account);

            if (str_contains($bio, $user->google_id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $gamertag
     *
     * @return mixed
     */
    private function getAccount($gamertag)
    {
        $lowercase = Text::seoGamertag($gamertag);

        return Account::where('seo', $lowercase)->first();
    }
}
