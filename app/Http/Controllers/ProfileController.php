<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\URL;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use PandaLove\Commands\UpdateAccount;
use PandaLove\Http\Requests;

use Illuminate\Http\Request;

class ProfileController extends Controller {

    private $request;

    private $inactiveCounter = 10;
    private $refreshRateInMinutes = 10;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

	public function index($gamertag = '', $characterId = '')
    {
        try
        {
            $account = Account::with('characters')
                ->where('seo', Text::seoGamertag($gamertag))
                ->firstOrFail();

            // setup hash cache
            Hashes::cacheAccountHashes($account);

            return view('profile', [
                'account' => $account,
                'characterId' => ($account->characterExists($characterId) ? $characterId : false)
            ]);
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404, 'Da Gone!!! We have no idea what you are looking for.');
        }
    }

    public function manualUpdate($seo)
    {
        if (\Auth::check())
        {
            try
            {
                $account = Account::with('characters')->where('seo', $seo)->firstOrFail();

                $inactive = $account->inactiveCounter;

                $this->dispatch(new UpdateAccount($account));

                // reload account
                $account = Account::with('characters')->where('seo', $seo)->firstOrFail();

                if ($account->inactiveCounter > $inactive)
                {
                    // they manually refreshed a profile with no data changes. ugh
                    return redirect('profile/' . $seo)
                        ->with('flash_message', [
                            'close' => 'true',
                            'type' => 'yellow',
                            'header' => 'Uh oh',
                            'body' => 'No data changed! Please do not update accounts unless you know they are out of date.'
                        ]);
                }
                else
                {
                    return redirect('profile/' . $seo);
                }
            }
            catch (ModelNotFoundException $e)
            {
                \App::abort(404);
            }
        }
        else
        {
            return redirect('profile/' . $seo)
                ->with('flash_message', [
                    'close' => 'true',
                    'type' => 'yellow',
                    'header' => 'Uh oh',
                    'body' => 'You must be signed in to manually update accounts'
                ]);
        }
    }

    public function checkForUpdate($gamertag = '')
    {
        if ($this->request->ajax() && ! \Agent::isRobot())
        {
            try
            {
                $account = Account::with('characters')
                    ->where('seo', Text::seoGamertag($gamertag))
                    ->firstOrFail();

                // We don't care about non-panda members
                if (! $account->isPandaLove())
                {
                    $this->inactiveCounter = 1;
                }

                // check for 10 inactive checks
                if ($account->inactiveCounter >= $this->inactiveCounter)
                {
                    return response()->json([
                        'updated' => false,
                        'frozen' => true,
                        'last_update' => 'This account hasn\'t had new data in awhile. - <a href="' .
                            URL::action('ProfileController@manualUpdate', [$account->seo]) . '" class="ui  horizontal green label no_underline">Update Manually</a>'
                    ]);
                }

                $char = $account->firstCharacter();

                if ($char->updated_at->diffInMinutes() >= $this->refreshRateInMinutes)
                {
                    // update this
                    $this->dispatch(new UpdateAccount($account));

                    return response()->json([
                        'updated' => true,
                        'frozen' => false,
                        'last_update' => $char->getLastUpdatedRelative()
                    ]);
                }

                return response()->json([
                    'updated' => false,
                    'frozen' => false,
                    'last_update' => $char->getLastUpdatedRelative()
                ]);
            }
            catch (ModelNotFoundException $e)
            {
                return response()->json([
                    'error' => 'Gamertag not found'
                ]);
            }
        }
    }
}
