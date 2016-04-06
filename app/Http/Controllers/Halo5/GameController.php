<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Onyx\Halo5\Objects\Match;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;

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
        die('unfinished');
    }

    public function getGame($matchId)
    {
        try
        {
            $match = Match::with('events.assists', 'events.killer_weapon', 'events.victim_weapon', 'events.victim', 'events.killer')
                ->where('uuid', $matchId)->firstOrFail();

            return $match;
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404);
        }
    }

    //---------------------------------------------------------------------------------
    // Halo 5 POST
    //---------------------------------------------------------------------------------
}
