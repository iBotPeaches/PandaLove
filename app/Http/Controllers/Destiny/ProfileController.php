<?php namespace PandaLove\Http\Controllers\Destiny;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\URL;
use Onyx\Account;
use Onyx\Destiny\Enums\Console;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\GamePlayer;
use PandaLove\Commands\UpdateDestinyAccount;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;
use Illuminate\Http\Request;

class ProfileController extends Controller {

    private $request;

    private $inactiveCounter = 10;
    private $refreshRateInMinutes = 520;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function platformSwitch($gamertag)
    {
        $accounts = Account::where('seo', Text::seoGamertag($gamertag))->get();

        return view('destiny.platform-switch', [
            'accounts' => $accounts
        ]);
    }

    public function index($console = Console::Xbox, $gamertag = '', $characterId = '')
    {
        try
        {
            /** @var $account Account */
            $account = Account::with('destiny.characters')
                ->where('seo', Text::seoGamertag($gamertag))
                ->where('accountType', $console)
                ->firstOrFail();

            $games = GamePlayer::with('game')
                ->select('destiny_game_players.*', 'destiny_games.occurredAt')
                ->leftJoin('destiny_games', 'destiny_game_players.game_id', '=', 'destiny_games.instanceId')
                ->where('membershipId', $account->destiny->membershipId)
                ->where('deaths', 0)
                ->where('destiny_games.instanceId', '!=', 0) // @todo task to remove orphaned GamePlayers & patch Game
                ->orderBy('destiny_games.occurredAt', 'DESC')
                ->get();

            $games->each(function($game_player)
            {
                $game_player->url = $game_player->game->buildUrl();
            });

            // setup hash cache
            Hashes::cacheAccountHashes($account, $games);

            return view('destiny.profile', [
                'account' => $account,
                'games' => $games,
                'characterId' => ($account->destiny->characterExists($characterId) ? $characterId : false),
                'description' => ($account->isPandaLove() ? "PandaLove: " : null) . $account->gamertag . " Destiny Profile",
                'title' => $account->gamertag . ($account->isPandaLove() ? " (Panda Love Member)" : null)
            ]);
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404, 'Da Gone!!! We have no idea what you are looking for.');
        }
    }

    public function manualUpdate($console = Console::Xbox, $seo)
    {
        if (\Auth::check())
        {
            try
            {
                /** @var $account Account */
                $account = Account::with('destiny.characters')
                    ->where('accountType', $console)
                    ->where('seo', $seo)
                    ->firstOrFail();

                $inactive = $account->destiny->inactiveCounter;

                $this->dispatch(new UpdateDestinyAccount($account));

                // reload account
                $account = Account::with('destiny.characters')
                    ->where('accountType', $console)
                    ->where('seo', $seo)
                    ->firstOrFail();

                if ($account->destiny->inactiveCounter > $inactive)
                {
                    return redirect('destiny/profile/' . $account->accountType . "/" . $account->seo)
                        ->with('flash_message', [
                            'close' => 'true',
                            'type' => 'yellow',
                            'header' => 'Uh oh',
                            'body' => 'No data changed! Please do not update accounts unless you know they are out of date.'
                        ]);
                }
                else
                {
                    return redirect('destiny/profile/' . $account->accountType . "/" . $account->seo);
                }
            }
            catch (ModelNotFoundException $e)
            {
                \App::abort(404);
            }
        }
        else
        {
            return redirect('destiny/profile/' . $console . "/" . $seo)
                ->with('flash_message', [
                    'close' => 'true',
                    'type' => 'yellow',
                    'header' => 'Uh oh',
                    'body' => 'You must be signed in to manually update accounts'
                ]);
        }
    }

    public function checkForUpdate($console = Console::Xbox, $gamertag = '')
    {
        if ($this->request->ajax() && ! \Agent::isRobot())
        {
            try
            {
                /** @var $account Account */
                $account = Account::with('destiny.characters')
                    ->where('seo', Text::seoGamertag($gamertag))
                    ->where('accountType', $console)
                    ->firstOrFail();

                // We don't care about non-panda members
                if (! $account->isPandaLove())
                {
                    $this->inactiveCounter = 1;
                }

                // check for 10 inactive checks
                if ($account->destiny->inactiveCounter >= $this->inactiveCounter)
                {
                    return response()->json([
                        'updated' => false,
                        'frozen' => true,
                        'last_update' => 'This account hasn\'t had new data in awhile. - <a href="' .
                            URL::action('Destiny\ProfileController@manualUpdate', [$account->accountType, $account->seo]) . '" class="ui  horizontal green label no_underline">Update Manually</a>'
                    ]);
                }

                $char = $account->destiny->firstCharacter();

                if ($char->updated_at->diffInMinutes() >= $this->refreshRateInMinutes)
                {
                    // update this
                    $this->dispatch(new UpdateDestinyAccount($account));

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