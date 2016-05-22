<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Onyx\Halo5\Client;
use Onyx\Halo5\GameNotReadyException;
use Onyx\Halo5\Helpers\Utils\Game;
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
                'match' => $match,
                'combined' => Game::buildQuickGameStats($match)
            ]);
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404);
        }
    }

    public function getMatchEvents($type, $matchId, $api = false)
    {
        try
        {
            $client = new Client();
            $match = $client->getGameByGameId($type, $matchId, true);

            if ($api) {
                return $match;
            }
            return view('halo5.games.events', [
                'match' => $match,
                'type' => $type,
                'combined' => Game::buildCombinedMatchEvents($match),
                'chart_data' => Game::buildKillChartArray($match),
            ]);
        }
        catch (ModelNotFoundException $e)
        {
            \App::abort(404);
        }
        catch (GameNotReadyException $e)
        {
            return \Redirect::to('/h5/games/game/' . $type . "/" . $matchId)
                ->with('flash_message', [
                    'type' => 'yellow',
                    'header' => 'Uh Oh',
                    'close' => true,
                    'body' => $e->getMessage()
                ]);
        }
    }

    //---------------------------------------------------------------------------------
    // Halo 5 POST
    //---------------------------------------------------------------------------------
}
