<?php namespace PandaLove\Events;

use Laravel\Socialite\Two\User;

use Illuminate\Queue\SerializesModels;

class GoogleLoggedIn extends Event {

	use SerializesModels;

	public $user;

	/**
	 * Create a new event instance.
	 * @param \Laravel\Socialite\Two\User $user
	 */
	public function __construct(User $user)
	{
		$this->user = $user;
	}

}
