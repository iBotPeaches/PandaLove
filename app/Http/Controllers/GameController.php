<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Destiny\Objects\Game;
use PandaLove\Http\Requests;

class GameController extends Controller {

    public function getIndex()
    {
        $raids = Game::where('type', 'Raid')->orderBy('occurredAt', 'DESC')->limit(10)->get();

        return view('games.index')
            ->with('raids', $raids);
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

    public function getHistory($category = '')
    {
        $allowed = ['Raid', 'Flawless', 'RaidTuesdays'];

        if (in_array($category, $allowed))
        {
            if ($category == "RaidTuesdays")
            {
                $raids = Game::where('type', 'Raid')
                    ->where('raidTuesday', '!=', 0)
                    ->orderBy('occurredAt', 'DESC')
                    ->groupBy('raidTuesday')
                    ->paginate(10);
            }
            else
            {
                $raids = Game::where('type', $category)
                    ->with('players.account')
                    ->orderBy('occurredAt', 'DESC')
                    ->paginate(10);
            }

            return view('games.history')
                ->with('raids', $raids);
        }
        else
        {
            \App::abort(404);
        }
    }

}
