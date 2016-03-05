<?php namespace PandaLove\Http\Controllers\Destiny;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Objects\Comment;
use Onyx\Destiny\Objects\Game;
use Onyx\Destiny\Helpers\Utils\Game as GameHelper;

use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;
use PandaLove\Http\Requests\AddCommentRequest;
use PandaLove\Http\Requests\deleteGameRequest;

class GameController extends Controller {

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    //---------------------------------------------------------------------------------
    // Destiny GET
    //---------------------------------------------------------------------------------

    public function getIndex()
    {
        $p = $this->isPanda;

        $raids = Game::raid($p)->singular()->limit(4)->get();
        $flawless = Game::flawless($p)->singular()->limit(4)->get();
        $tuesday = Game::tuesday($p)->limit(4)->get();
        $pvp = Game::with('pvp')->multiplayer($p)->singular()->limit(5)->get();
        $poe = Game::poe($p)->singular()->limit(4)->get();
        $passages = GameHelper::getMaps(Game::with('pvp', 'players.account.destiny', 'players.account.user')->passage()->limit(4)->get());

        Hashes::cacheGameHashes($raids, $flawless, $tuesday, $pvp, $poe, $passages);

        return view('destiny.games.index')
            ->with('raids', $raids)
            ->with('flawless', $flawless)
            ->with('tuesday', $tuesday)
            ->with('pvp', $pvp)
            ->with('poe', $poe)
            ->with('passages', $passages)
            ->with('title', 'PandaLove Game Index')
            ->with('description', 'PandaLove games including Raids, PVP, Trials of Osiris, Prison of Elders and much more.');
    }

    public function getGame($instanceId, $all = false)
    {
        try
        {
            $game = Game::with(['comments.player' => function($query) use ($instanceId)
                {
                    $query->where('game_id', $instanceId);
                }, 'players.gameChar', 'players.account.destiny', 'comments.account', 'players.account.user'
                ])
                ->where('instanceId', $instanceId)
                ->firstOrFail();

            // cache of hashes
            Hashes::cacheSingleGameHashes($game);

            $gts = '';
            $revives = false;

            $game->players->each(function($player) use (&$gts, &$revives)
            {
                $player->kd = $player->kdr();
                $gts .= $player->account->gamertag . ", ";

                if ($player->revives_given != 0)
                {
                    $revives = true;
                }
            });

            // shared views
            \View::share('game', $game);
            \View::share('showAll', boolval($all));
            \View::share('description', $game->type()->title . " with players: " . rtrim($gts, ", "));
            \View::share('title', 'PandaLove: ' . $game->type()->title);
            \View::share('revives', $revives);

            if ($game->type == "PVP")
            {
                // This is a fucking mess, but we don't have KD in the table so can't leverage the DB
                // We append score (1501) to KD (multiplied by 100 to remove decimal)
                // Thus 1501 w/ KD of .44 becomes 1501.00000044 (padded 8 times)
                $game->players = $game->players->sortBy(function($player)
                {
                    return sprintf('%08d.%08d', $player->getOriginal('score'), ($player->kd * 100));
                }, SORT_REGULAR, true);
                return view('destiny.games.pvp');
            }
            else
            {
                $game->players = $game->players->sortByDesc('kd');
                return view('destiny.games.game');
            }
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404);
        }
    }

    public function getTuesday($raidTuesday, $gameId = null)
    {
        $games = Game::with('players.gameChar', 'players.account.destiny')
            ->OfTuesday($raidTuesday)
            ->get();

        if ($games->isEmpty())
        {
            \App::abort(404);
        }

        Hashes::cacheTuesdayHashes($games);
        $combined = GameHelper::buildCombinedStats($games);

        return view('destiny.games.tuesday')
            ->with('raidTuesday', intval($raidTuesday))
            ->with('revives', $combined['stats']['revives'])
            ->with('games', $games)
            ->with('showAll', false)
            ->with('combined', $combined)
            ->with('title', 'PandaLove Raid Tuesday: ' . $raidTuesday)
            ->with('description', 'PandaLove Raid Tuesday: ' . $raidTuesday . ' including ' . count($games) . ' raids.')
            ->with('gameId', GameHelper::gameIdExists($games, $gameId));
    }

    public function getPassage($passageId, $gameId = null)
    {
        $games = Game::with('players.account.user', 'players.gameChar', 'players.account.destiny', 'pvp')
            ->ofPassage($passageId)
            ->get();

        if ($games->isEmpty())
        {
            \App::abort(404);
        }

        Hashes::cacheTuesdayHashes($games);
        $combined = GameHelper::buildCombinedStats($games);
        $passageCombined = GameHelper::buildQuickPassageStats($games);

        return view('destiny.games.passage')
            ->with('passageId', intval($passageId))
            ->with('revives', $combined['stats']['revives'])
            ->with('games', $games)
            ->with('combined', $combined)
            ->with('passage', $passageCombined)
            ->with('title', 'PandaLove: Trials Of Osiris #' . $passageId)
            ->with('description', 'PandaLove: Trials Of Osiris #' . $passageId)
            ->with('showAll', true)
            ->with('gameId', GameHelper::gameIdExists($games, $gameId));
    }

    public function getHistory($category = '')
    {
        $raids = null;

        $title = 'Raid';
        $description = '';
        $p = $this->isPanda;

        switch ($category)
        {
            case "Raid":
                $description = 'Raids';
                $raids = Game::raid($p)
                    ->singular()
                    ->with('players.historyAccount.user')
                    ->paginate(10);
                break;

            case "Flawless";
                $description = 'Flawless Raids';
                $raids = Game::flawless($p)
                    ->singular()
                    ->with('players.historyAccount.user')
                    ->paginate(10);
                break;

            case "RaidTuesdays";
                $description = 'Raid Tuesdays';
                $raids = Game::tuesday($p)
                    ->with('players.historyAccount.user')
                    ->paginate(10);
                break;

            case "PVP":
                $description = 'PVP';
                $title = 'Gametype';
                $raids = Game::multiplayer($p)
                    ->singular()
                    ->with('players.historyAccount.user', 'pvp')
                    ->paginate(10);
                break;

            case "PoE":
                $description = 'Prison Of Elders';
                $raids = Game::poe($p)
                    ->singular()
                    ->with('players.historyAccount.user')
                    ->paginate(10);
                break;

            case "ToO":
                $description = 'Trials Of Osiris';
                $title = 'Gametype';
                $raids = Game::passage()
                    ->with('players.historyAccount.user')
                    ->paginate(10);
                break;

            default:
                \App::abort(404);
                break;
        }

        Hashes::cacheHistoryHashes($raids);

        return view('destiny.games.history')
            ->with('raids', $raids)
            ->with('t_header', $title)
            ->with('title', 'PandaLove: ' . $description . ' History')
            ->with('description', 'PandaLove complete history of: ' . $description);
    }

    //---------------------------------------------------------------------------------
    // Destiny POST
    //---------------------------------------------------------------------------------

    public function deleteGame(deleteGameRequest $request)
    {
        $game = Game::where('instanceId', $request->get('game_id'))->first();
        $game->delete();

        return \Redirect::action('Destiny\GameController@getIndex')
            ->with('flash_message', [
                'type' => 'green',
                'header' => 'Game Delete!',
                'close' => true,
                'body' => 'You deleted gameId (' . $request->get('game_id') . ") "
            ]);
    }

    public function postToggleGameVisibility(deleteGameRequest $request)
    {
        try
        {
            $game = Game::where('instanceId', $request->get('game_id'))->firstOrFail();
            $game->hidden = ! $game->hidden;
            $game->save();

            if ($game->hidden)
            {
                $msg = 'Game was hidden from public.';
            }
            else
            {
                $msg = 'Game is now visible to public';
            }

            return \Redirect::action('Destiny\GameController@getGame', array($request->get('game_id')))
                ->with('flash_message', [
                    'type' => 'green',
                    'header' => 'Game Visibility Toggled!',
                    'close' => true,
                    'body' => $msg
                ]);

        }
        catch (ModelNotFoundException $e)
        {
            return \Redirect::action('Destiny\GameController@getIndex');
        }
    }
}
