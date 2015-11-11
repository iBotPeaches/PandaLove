<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\URL;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\GamePlayer;
use PandaLove\Commands\UpdateAccount;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;

use Illuminate\Http\Request;

class ProfileController extends Controller {

    private $request;

    private $inactiveCounter = 10;
    private $refreshRateInMinutes = 60;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function index($gamertag)
    {
        try
        {
            $account = Account::with('h5')
                ->where('seo', Text::seoGamertag($gamertag))
                ->firstOrFail();

            return view('halo5.profile', [
                'account' => $account,
                'title' => $account->gamertag . ($account->isPandaLove() ? " (Panda Love Member)" : null)
            ]);
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404, 'We could not find this Halo5 Profile.');
        }
    }
}