<?php namespace PandaLove\Http\Controllers;

use Onyx\User;
use PandaLove\Events\GoogleLoggedIn;
use PandaLove\Http\Requests;
use Illuminate\Http\Request;

class AuthController extends Controller {

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogin()
    {
        return $this->redirectToProvider();
    }

    public function getCallback()
    {
        return $this->handleProviderCallback();
    }

    private function redirectToProvider()
    {
        return \Socialite::with('google')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = \Socialite::with('google')->user();

        \Event::fire(new GoogleLoggedIn($user));
        \Auth::login(User::where('google_id', $user->id)->first(), true);

        return \Redirect::to('/');
    }

}
