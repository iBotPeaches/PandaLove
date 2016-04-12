<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Onyx\Halo5\Client;
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

    public function getGame($type, $matchId, $api = false)
    {
        try
        {
            $client = new Client();
            $match = $client->getGameByGameId($type, $matchId);

            if ($api) {
                return $match;
            }
            return view('halo5.games.game', [
                'type' => $type,
                'match' => $match
            ]);
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404);
        }
    }

    public function getMatchEvents($type, $matchId)
    {
        try
        {
            $client = new Client();
            $match = $client->getGameByGameId($type, $matchId, true);

            return view('halo5.games.events', [
                'match' => $match,
                'type' => $type,
            ]);
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
