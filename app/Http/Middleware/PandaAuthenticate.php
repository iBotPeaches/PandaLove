<?php namespace PandaLove\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Onyx\Account;
use Onyx\User;

class PandaAuthenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		/** @var $user User */
		$user = $this->auth->user();

		if ($user != null && $user->isPanda)
		{
			return $next($request);
		}
		else
		{
			return redirect('/')->with('flash_message', [
				'body' => 'No permission to view Calendar. Please sign in and be apart of PandaLove.',
				'type' => 'yellow'
			]);
		}
	}

}
