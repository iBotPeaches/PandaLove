<?php

namespace PandaLove\Http\Controllers\Fortnite;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Onyx\Fortnite\Objects\Stats;
use Onyx\XboxLive\Enums\Console;
use PandaLove\Http\Controllers\Controller;

/**
 * Class ProfileController.
 */
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

    public function index(string $id)
    {
        try {
            $stats = Stats::where('epic_id', $id)->firstOrFail();

            return view('fortnite.profile', [
                'account' => $stats->account,
                'stats' => $stats
            ]);
        } catch (ModelNotFoundException $e) {
            \App::abort(404, 'We could not find this Fortnite Profile.');
        }
    }

    public function checkForUpdate($gamertag = '', $platform = Console::Xbox)
    {

    }

    public function manualUpdate($gamertag, $platform = Console::Xbox)
    {
    }
}
