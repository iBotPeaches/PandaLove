<?php

namespace PandaLove\Http\Requests;

use PandaLove\Http\Requests\Request;

class AdminAddHalo5GamertagRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = \Auth::user();

        if ($user != null && $user->admin)
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
            'gamertag' => 'required|min:3|gamertag-real'
        ];
    }
}
