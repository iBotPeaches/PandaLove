<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Objects\Comment;
use Onyx\Destiny\Objects\Game;
use Onyx\Destiny\Helpers\Utils\Game as GameHelper;

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

    public function getIndex()
    {
        $raids = Game::raid()->singular()->limit(4)->get();
        $flawless = Game::flawless()->singular()->limit(4)->get();
        $tuesday = Game::tuesday()->limit(4)->get();
        $pvp = Game::with('pvp')->multiplayer()->singular()->limit(10)->get();

        Hashes::cacheGameHashes($raids, $flawless, $tuesday, $pvp);

        return view('games.index')
            ->with('raids', $raids)
            ->with('flawless', $flawless)
            ->with('tuesday', $tuesday)
            ->with('pvp', $pvp);
    }

    public function getGame($instanceId, $all = false)
    {
        try
        {
            $game = Game::with(['comments.player' => function($query) use ($instanceId)
                {
                    $query->where('game_id', $instanceId);
                }, 'players.gameChar', 'players.account', 'comments.account'
                ])
                ->where('instanceId', $instanceId)
                ->firstOrFail();

            $game->players->each(function($player)
            {
                $player->kd = $player->kdr();
            });

            // shared views
            \View::share('game', $game);
            \View::share('showAll', boolval($all));

            // cache of hashes
            Hashes::cacheSingleGameHashes($game);

            if ($game->type == "PVP")
            {
                $game->players->sortByDesc('score');
                return view('games.pvp');
            }
            else
            {
                $game->players->sortByDesc('kd');
                return view('games.game');
            }
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404);
        }
    }

    public function deleteGame(deleteGameRequest $request)
    {
        $game = Game::where('instanceId', $request->get('game_id'))->first();
        $game->delete();

        return \Redirect::to('/games')
            ->with('flash_message', [
                'type' => 'green',
                'header' => 'Game Delete!',
                'close' => true,
                'body' => 'You deleted gameId (' . $request->get('game_id') . ") "
            ]);
    }

    public function getTuesday($raidTuesday)
    {
        $games = Game::with('players.gameChar', 'players.account')
            ->OfTuesday($raidTuesday)
            ->get();

        if ($games->isEmpty())
        {
            \App::abort(404);
        }

        Hashes::cacheTuesdayHashes($games);
        $combined = GameHelper::buildCombinedStats($games);

        return view('games.tuesday')
            ->with('raidTuesday', intval($raidTuesday))
            ->with('games', $games)
            ->with('combined', $combined);
    }

    public function getHistory($category = '')
    {
        $raids = null;

        $title = 'Raid';

        switch ($category)
        {
            case "Raid":
                $raids = Game::raid()
                    ->singular()
                    ->with('players.historyAccount')
                    ->paginate(10);
                break;

            case "Flawless";
                $raids = Game::flawless()
                    ->singular()
                    ->with('players.historyAccount')
                    ->paginate(10);
                break;

            case "RaidTuesdays";
                $raids = Game::tuesday()
                    ->with('players.historyAccount')
                    ->paginate(10);
                break;

            case "PVP":
                $title = 'Gametype';
                $raids = Game::multiplayer()
                    ->singular()
                    ->with('players.historyAccount', 'pvp')
                    ->paginate(10);
                break;

            default:
                \App::abort(404);
                break;
        }

        Hashes::cacheHistoryHashes($raids);

        return view('games.history')
            ->with('raids', $raids)
            ->with('title', $title);
    }

    public function postComment(AddCommentRequest $request)
    {
        $game = Game::where('instanceId', $request->get('game_id'))->first();
        $membershipId =  $this->user->account->membershipId;

        $comment = new Comment();
        $comment->comment = $request->get('message');
        $comment->membershipId = $membershipId;
        $comment->characterId = $game->findAccountViaMembershipId($membershipId, false)->characterId;
        $comment->parent_comment_id = 0;

        $game->comments()->save($comment);

        return response()->json(['flag' => true, 'url' => \URL::action('GameController@getGame', $game->instanceId)]);
    }

}
