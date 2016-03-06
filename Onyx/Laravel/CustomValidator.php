<?php namespace Onyx\Laravel;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;
use Onyx\Account;
use Onyx\Destiny\GameNotFoundException;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Character;
use Onyx\Destiny\Objects\Game;
use Onyx\Calendar\Objects\Event as GameEvent;
use Onyx\Destiny\PlayerNotFoundException;
use Onyx\User;

use Onyx\Halo5\Client as Halo5Client;
use Onyx\Destiny\Client as DestinyClient;
use Onyx\XboxLive\Client as XboxClient;

class CustomValidator extends Validator {

    public function validateGameReal($attribute, $value, $parameters)
    {
        $client = new DestinyClient();

        try
        {
            $game = $client->fetchGameByInstanceId($value);
        }
        catch (GameNotFoundException $e)
        {
            return false;
        }

        return true;
    }

    public function validateGameExistsReal($attribute, $value, $parameters)
    {
        try
        {
            $game = Game::where('instanceId', $value)->firstOrFail();
        }
        catch (ModelNotFoundException $e)
        {
            return false;
        }

        return true;
    }

    public function validateGamertagReal($attribute, $value, $parameters)
    {
        $client = new DestinyClient();

        try
        {
            $account = $client->fetchAccountByGamertag(1, $value);
        }
        catch (PlayerNotFoundException $e)
        {
            return false;
        }

        return true;
    }

    public function validateDestinyTagExists($attribute, $value, $parameters)
    {
        $client = new DestinyClient();

        try
        {
            $account = $client->searchAccountByName($value);
        }
        catch (PlayerNotFoundException $ex)
        {
            return false;
        }

        return true;
    }

    public function validateH5GamertagReal($attribute, $value, $parameters)
    {
        $client = new Halo5Client();

        try
        {
            $account = $client->getAccountByGamertag($value);
        }
        catch (PlayerNotFoundException $e)
        {
            return false;
        }

        return true;
    }

    public function validateCharacterReal($attribute, $value, $parameters)
    {
        try
        {
            $character = Character::where('characterId', $value)->firstOrFail();
        }
        catch (ModelNotFoundException $e)
        {
            return false;
        }

        return true;
    }

    public function validateEventExists($attribute, $value, $parameters)
    {
        try
        {
            $event = GameEvent::where('id', $value)->firstOrFail();
        }
        catch (ModelNotFoundException $e)
        {
            return false;
        }

        return true;
    }

    public function validateGamertagExists($attribute, $value, $parameters)
    {
        $account = $this->getAccount($value);

        if ($account instanceof Account && $account->user == null)
        {
            return true;
        }

        return false;
    }

    public function validateMottoContains($attribute, $value, $parameters)
    {
        $account = $this->getAccount($value);
        $user = \Auth::user();

        if ($account instanceof Account && $user instanceof User)
        {
            $client = new XboxClient();
            $bio = $client->fetchAccountBio($account);

            if (str_contains($bio, $user->google_id))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $gamertag
     * @return mixed
     */
    private function getAccount($gamertag)
    {
        $lowercase = Text::seoGamertag($gamertag);

       return Account::where('seo', $lowercase)->first();
    }
}