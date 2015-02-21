<?php namespace Onyx\Laravel;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;
use Onyx\Account;
use Onyx\Destiny\Client;
use Onyx\Destiny\GameNotFoundException;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Game;
use Onyx\Destiny\PlayerNotFoundException;
use Onyx\User;

class CustomValidator extends Validator {

    public function validateGameReal($attribute, $value, $parameters)
    {
        $client = new Client();

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
        $client = new Client();

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
            $client = new Client();
            $json = $client->getBungieProfile($account);

            if ($json != null && str_contains($json['about'], $user->google_id))
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