<?php namespace PandaLove\Http\Controllers;

use Onyx\Destiny\Objects\Game;
use PandaLove\Http\Requests;
use PandaLove\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GameController extends Controller {

    public function getIndex()
    {
        $raids = Game::where('type', 'Raid')->orderBy('occurredAt', 'DESC')->limit(10)->get();

        return view('games.index')
            ->with('raids', $raids);
    }

}
