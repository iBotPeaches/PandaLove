<?php

namespace PandaLove\Http\Requests;

use Illuminate\Support\Facades\Input;

class AddDestinyGamertagRequest extends Request
{
    protected $errorBag = 'destiny';

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
        Input::merge(array_map('trim', Input::all()));

        return [
            'gamertag' => 'required|min:3|destiny-tag-exists',
        ];
    }
}
