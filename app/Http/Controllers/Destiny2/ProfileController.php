<?php

namespace PandaLove\Http\Controllers\Destiny2;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\XboxLive\Enums\Console;
use PandaLove\Http\Controllers\Controller;

class ProfileController extends Controller
{
    private $request;

    private $inactiveCounter = 10;
    private $refreshRateInMinutes = 520;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function index($console = Console::Xbox, $gamertag = '', $characterId = '')
    {
        try {
            /** @var $account Account */
            $account = Account::with('destiny2')
                ->where('seo', Text::seoGamertag($gamertag))
                ->where('accountType', $console)
                ->firstOrFail();

            if (!isset($account->destiny2)) {
                \App::abort(404, 'This account no longer has Destiny Data. It must have been renamed.');
            }

            return view('destiny2.profile', [
                'account' => $account,
            ]);
        } catch (ModelNotFoundException $e) {
            \App::abort(404, 'Da Gone!!! We have no idea what you are looking for.');
        }
    }
}
