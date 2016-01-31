<?php

namespace PandaLove\Http\Requests;

use Onyx\Account;
use PandaLove\Http\Requests\Request;

class AddRSVP extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = \Auth::user();

        if ($user != null && $user->account instanceof Account && $user->account->isPandaLove())
        {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'character' => 'character-real'
        ];
    }
}
