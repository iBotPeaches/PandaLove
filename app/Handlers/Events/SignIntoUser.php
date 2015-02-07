<?php namespace PandaLove\Handlers\Events;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Onyx\User;
use PandaLove\Events\GoogleLoggedIn;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class SignIntoUser {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  GoogleLoggedIn   $event
	 * @return void
	 */
	public function handle(GoogleLoggedIn $event)
	{
		$user = $event->user;

		try
		{
			$mUser = User::where('google_id', $user->id)->firstOrFail();
			$mUser->avatar = $user->getAvatar();
			$mUser->google_url = $user->user['link'];
			$mUser->name = $user->getName();
		}
		catch (ModelNotFoundException $e)
		{
			$mUser = new User();
			$mUser->name = $user->getName();
			$mUser->email = $user->getEmail();
			$mUser->google_id = $user->user['id'];
			$mUser->avatar = $user->getAvatar();
			$mUser->google_url = $user->user['link'];
		}

		$mUser->save();
	}
}
