<?php namespace PandaLove\Http\Requests;

use PandaLove\Http\Requests\Request;

class OwnershipFormRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if (\Auth::check())
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
			'gamertag' => 'required|min:3|gamertag-exists|motto-contains'
		];
	}

}
