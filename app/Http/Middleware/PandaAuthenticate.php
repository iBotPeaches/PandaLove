<?php namespace PandaLove\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Onyx\Account;

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
		$user = $this->auth->user();

		if ($user != null && $user->account instanceof Account && $user->account->isPandaLove())
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
