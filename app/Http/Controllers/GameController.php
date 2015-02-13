<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Destiny\Objects\Game;
use PandaLove\Http\Requests;

class GameController extends Controller {

    public function getIndex()
    {
        $raids = Game::raid()->singular()->limit(4)->get();
        $flawless = Game::flawless()->singular()->limit(4)->get();
        $tuesday = Game::tuesday()->limit(4)->get();

        return view('games.index')
            ->with('raids', $raids)
            ->with('flawless', $flawless)
            ->with('tuesday', $tuesday);
    }

    public function getGame($instanceId, $all = false)
    {
        try
        {
            $game = Game::with('players.character', 'players.account')->where('instanceId', $instanceId)->firstOrFail();

            $game->players->each(function($player)
            {
                $player->kd = $player->kdr();
            })->sortByDesc('kd');

            return view('games.game')
                ->with('game', $game)
                ->with('showAll', boolval($all));
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404);
        }
    }

    public function getTuesday($raidTuesday)
    {
        dd($raidTuesday);
    }

    public function getHistory($category = '')
    {
        $raids = null;

        switch ($category)
        {
            case "Raid":
                $raids = Game::raid()
                    ->singular()
                    ->with('players.account')
                    ->paginate(10);
                break;

            case "Flawless";
                $raids = Game::flawless()
                    ->singular()
                    ->with('players.account')
                    ->paginate(10);
                break;

            case "RaidTuesdays";
                $raids = Game::tuesday()
                    ->with('players.account')
                    ->paginate(10);
                break;

            default:
                \App::abort(404);
                break;
        }

        return view('games.history')
            ->with('raids', $raids);
    }

}
