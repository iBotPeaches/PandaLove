<?php

namespace PandaLove\Http\Requests;

use PandaLove\Http\Requests\Request;

class AddOverwatchRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        \Input::merge(array_map('trim', \Input::all()));

        return [
            'gamertag' => 'required|min:3|overwatch-real',
            'platform' => 'required|in:0,1,2'
        ];
    }
}