<?php

namespace PandaLove\Http\Controllers\Halo5;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Halo5\Client as Client;
use Onyx\Halo5\Collections\SeasonCollection;
use Onyx\Halo5\Helpers\String\Text as Halo5Text;
use Onyx\Halo5\Objects\Medal;
use Onyx\Halo5\Objects\Warzone;
use Onyx\Halo5\Objects\Weapon;
use Onyx\XboxLive\Enums\Console;
use PandaLove\Commands\UpdateHalo5Account;
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

    public function index($gamertag)
    {
        try {
            /** @var $account Account */
            $account = Account::with('h5.playlists.stock', 'h5.playlists.current_csr', 'h5.playlists.high_csr', 'h5.playlists.season', 'h5.warzone')
                ->where('seo', Text::seoGamertag($gamertag))
                ->where('accountType', Console::Xbox)
                ->firstOrFail();

            \Session::put('previousHaloProfile', [
                'seo'      => $account->seo,
                'gamertag' => $account->gamertag,
            ]);

            $seasons = new SeasonCollection($account, $account->h5->playlists);

            if ($account->h5->disabled) {
                return view('errors.404', [
                    'message' => 'This gamertag no longer exists. It existed at one point, but a rename must have happened.
                    We do not know the new gamertag.',
                ]);
            }

            return view('halo5.profile', [
                'account'     => $account,
                'playlists'   => $seasons->current(),
                'seasons'     => $seasons,
                'title'       => $account->gamertag.($account->isPandaLove() ? ' (Panda Love Member)' : null),
                'medals'      => Medal::getAll(),
                'weapons'     => Weapon::getAll(),
                'mMedals'     => $account->h5->medals,
                'progressBar' => Halo5Text::buildProgressBar($account),
            ]);
        } catch (ModelNotFoundException $e) {
            \App::abort(404, 'We could not find this Halo5 Profile.');
        }
    }

    public function checkForUpdate($gamertag = '')
    {
        if ($this->request->ajax() && !\Agent::isRobot()) {
            try {
                $account = Account::with('h5', 'h5.warzone')
                    ->where('seo', Text::seoGamertag($gamertag))
                    ->where('accountType', Console::Xbox)
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

    public function manualUpdate($seo)
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
                    \Log::warning('[H5]'.$account->gamertag.' was updated with no data ('.\Request::ip().')');
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

    public function getRecentGames($gamertag, $page = 0)
    {
        try {
            /** @var $account Account */
            $account = Account::with('h5')
                ->where('seo', Text::seoGamertag($gamertag))
                ->where('accountType', Console::Xbox)
                ->firstOrFail();

            $client = new Client();
            $games = $client->getPlayerMatches($account, 'arena,warzone', $page);

            return view('includes.halo5.profile.recent-tab', [
                'games' => $games,
                'page'  => $page,
            ])->render();
        } catch (ModelNotFoundException $e) {
            return view('includes.message', [
                'message' => [
                    'header' => 'Uh oh',
                    'type'   => 'red',
                    'body'   => 'We encountered a major error and could not recover :(',
                ],
            ]);
        } catch (ClientException $ex) {
            $this->dispatch(new UpdateHalo5Account($account));

            return view('includes.message', [
                'message' => [
                    'header' => 'Uh oh',
                    'type'   => 'red',
                    'body'   => 'We encountered a major error and could not recover :(',
                ],
            ]);
        }
    }
}
