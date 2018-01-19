<?php

namespace PandaLove\Http\Controllers\Fortnite;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Onyx\Fortnite\Objects\Stats;
use PandaLove\Commands\UpdateFortniteAccount;
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
                'stats'   => $stats,
            ]);
        } catch (ModelNotFoundException $e) {
            \App::abort(404, 'We could not find this Fortnite Profile.');
        }
    }

    public function checkForUpdate(string $id = '')
    {
        if ($this->request->ajax() && !\Agent::isRobot()) {
            try {
                /** @var Stats $stats */
                $stats = Stats::where('epic_id', $id)->firstOrFail();

                // We don't care about non-panda members
                if (!$stats->account->isPandaLove()) {
                    $this->inactiveCounter = 1;
                }

                // check for 10 inactive checks
                if ($stats->inactiveCounter >= $this->inactiveCounter) {
                    return response()->json([
                        'updated'     => false,
                        'frozen'      => true,
                        'last_update' => 'This account hasn\'t had new data in awhile. - <a href="'.
                            \URL::action('Fortnite\ProfileController@manualUpdate', [$stats->epic_id]).'" class="ui horizontal green label no_underline">Update Manually</a>',
                    ]);
                }

                if ($stats->updated_at->diffInMinutes() >= $this->refreshRateInMinutes) {
                    $this->dispatch(new UpdateFortniteAccount($stats->account));

                    return response()->json([
                        'updated'     => true,
                        'frozen'      => false,
                        'last_update' => $stats->getLastUpdatedRelative(),
                    ]);
                }

                return response()->json([
                    'updated'     => false,
                    'frozen'      => false,
                    'last_update' => $stats->getLastUpdatedRelative(),
                ]);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'Gamertag not found',
                ]);
            }
        }
    }

    public function manualUpdate(string $id = '')
    {
        if (\Auth::check()) {
            try {
                /** @var Stats $stats */
                $stats = Stats::where('epic_id', $id)->firstOrFail();

                $inactive = $stats->inactiveCounter;

                $this->dispatch(new UpdateFortniteAccount($stats->account));

                // reload account
                $stats = Stats::where('epic_id', $id)->firstOrFail();

                if ($stats->inactive_counter > $inactive) {
                    \Log::warning('[FN]'.$stats->account->gamertag.' was updated with no data ('.\Request::ip().')');

                    // they manually refreshed a profile with no data changes. ugh
                    return redirect('fortnite/profile/'.$stats->epic_id)
                        ->with('flash_message', [
                            'close'  => 'true',
                            'type'   => 'yellow',
                            'header' => 'Uh oh',
                            'body'   => 'No data changed! Please do not update accounts unless you know they are out of date.',
                        ]);
                } else {
                    return redirect('fortnite/profile/'.$stats->epic_id);
                }
            } catch (ModelNotFoundException $e) {
                \App::abort(404);
            }
        } else {
            return redirect('fortnite/profile/'.$id)
                ->with('flash_message', [
                    'close'  => 'true',
                    'type'   => 'yellow',
                    'header' => 'Uh oh',
                    'body'   => 'You must be signed in to manually update accounts',
                ]);
        }
    }
}
