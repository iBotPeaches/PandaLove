<?php

namespace PandaLove\Http\Requests;

/**
 * Class AddFortniteRequest
 * @package PandaLove\Http\Requests
 */
class AddFortniteRequest extends Request
{
    protected $errorBag = 'fortnite';

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
            'gamertag' => 'required|min:3|fortnite-real',
            //'platform' => 'required|in:0,1,2',
        ];
    }
}
