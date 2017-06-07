<?php

namespace PandaLove\Http\Controllers\Overwatch;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\XboxLive\Enums\Console;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Controllers\Controller;

/**
 * Class ProfileController
 * @package PandaLove\Http\Controllers\Overwatch
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

    public function index($gamertag, $platform = Console::Xbox)
    {
        try {
            /** @var $account Account */
            $account = Account::with('overwatch.characters')
                ->where('seo', Text::seoGamertag($gamertag))
                ->firstOrFail();

            return view('overwatch.profile', [
                'account'     => $account,
                'overall'     => $account->overwatch->first(),
                'main'        => $account->overwatch->first()->mainCharacter()
            ]);
        } catch (ModelNotFoundException $e) {
            \App::abort(404, 'We could not find this Overwatch Profile.');
        }
    }

    public function checkForUpdate($gamertag = '', $platform = Console::Xbox)
    {
        if ($this->request->ajax() && !\Agent::isRobot()) {
            try {
                $account = Account::with('h5', 'h5.warzone')
                    ->where('seo', Text::seoGamertag($gamertag))
                    ->firstOrFail();

                // We don't care about non-panda members
                if (!$account->isPandaLove()) {
                    $this->inactiveCounter = 1;
                }

                // check for 10 inactive checks
                if ($account->h5->inactiveCounter >= $this->inactiveCounter && $account->h5->inactiveCounter != 128) {
                    return response()->json([
                        'updated'     => false,
                        'frozen'      => true,
                        'last_update' => 'This account hasn\'t had new data in awhile. - <a href="'.
                            URL::action('Halo5\ProfileController@manualUpdate', [$account->seo]).'" class="ui  horizontal green label no_underline">Update Manually</a>',
                    ]);
                }

                if ($account->h5->updated_at->diffInMinutes() >= $this->refreshRateInMinutes || $account->h5->inactiveCounter == 128) {
                    // update this
                    $this->dispatch(new UpdateHalo5Account($account));

                    return response()->json([
                        'updated'     => true,
                        'frozen'      => false,
                        'last_update' => $account->h5->getLastUpdatedRelative(),
                    ]);
                }

                return response()->json([
                    'updated'     => false,
                    'frozen'      => false,
                    'last_update' => $account->h5->getLastUpdatedRelative(),
                ]);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'Gamertag not found',
                ]);
            }
        }
    }

    public function manualUpdate($gamertag, $platform = Console::Xbox)
    {
        if (\Auth::check()) {
            try {
                $account = Account::with('h5.playlists.stock')
                    ->where('seo', $seo)
                    ->firstOrFail();

                $inactive = $account->h5->inactiveCounter;

                $this->dispatch(new UpdateHalo5Account($account));

                // reload account
                $account = Account::with('h5.playlists.stock')->where('seo', $seo)->firstOrFail();

                if ($account->h5->inactiveCounter > $inactive) {
                    // they manually refreshed a profile with no data changes. ugh
                    return redirect('h5/profile/'.$seo)
                        ->with('flash_message', [
                            'close'  => 'true',
                            'type'   => 'yellow',
                            'header' => 'Uh oh',
                            'body'   => 'No data changed! Please do not update accounts unless you know they are out of date.',
                        ]);
                } else {
                    return redirect('h5/profile/'.$seo);
                }
            } catch (ModelNotFoundException $e) {
                \App::abort(404);
            }
        } else {
            return redirect('h5/profile/'.$seo)
                ->with('flash_message', [
                    'close'  => 'true',
                    'type'   => 'yellow',
                    'header' => 'Uh oh',
                    'body'   => 'You must be signed in to manually update accounts',
                ]);
        }
    }
}
