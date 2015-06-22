<?php namespace PandaLove\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Onyx\User;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	public $user;

    public $isPanda = false;

	function __construct()
	{
		$this->user = \Auth::user();

        if ($this->user instanceof User)
        {
            if (isset($this->user->account->gamertag) && $this->user->account->isPandaLove())
            {
                $this->isPanda = true;
            }
        }

        \View::share('isPanda', $this->isPanda);
		\View::share('user', $this->user);
	}

}
