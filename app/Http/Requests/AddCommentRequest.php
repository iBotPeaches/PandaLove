<?php namespace PandaLove\Http\Requests;

use PandaLove\Http\Requests\Request;

class AddCommentRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = \Auth::user();

        if ($user != null && $user->account != null)
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
            'message' => 'required',
            'game_id' => 'required|game-exists-real'
        ];
    }

}
