<?php namespace Onyx\Laravel;

use Illuminate\Validation\Validator;
use Onyx\Account;
use Onyx\Destiny\Client;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\User;

class CustomValidator extends Validator {

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