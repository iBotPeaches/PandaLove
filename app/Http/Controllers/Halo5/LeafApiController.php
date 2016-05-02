<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\View\Factory as View;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\CSR;
use Onyx\Halo5\Objects\Playlist;
use Onyx\Halo5\Objects\Season;
use PandaLove\Http\Controllers\Controller;

class LeafApiController extends Controller {

    private $view;
    private $request;
    private $redirect;

    protected $layout = "layouts.master";

    public function __construct(View $view, Redirect $redirect, Request $request)
    {
        parent::__construct();
        $this->view = $view;
        $this->request = $request;
        $this->redirect = $redirect;
        date_default_timezone_set('America/Chicago');
    }

    //---------------------------------------------------------------------------------
    // Halo5 GET
    //---------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function getSeasons()
    {
        $seasons = Season::with('playlists')->orderBy('start_date', 'DESC')->get();

        return Response::json([
            'error' => false,
            'seasons' => $seasons
        ], 200);
    }

    /**
     * @return mixed
     */
    public function getCsrs()
    {
        $csrs = CSR::all();

        return Response::json([
            'error' => false,
            'csrs' => $csrs
        ], 200);
    }

    /**
     * @param $seasonId
     * @param $playlistId
     * @return mixed
     */
    public function getLeaderboard($seasonId, $playlistId)
    {
        try
        {
            $client = new Client();
            $leaderboard = $client->getLeaderboardViaSeasonAndPlaylist($seasonId, $playlistId);

            return Response::json([
                'error' => false,
                'leaderboard' => $leaderboard
            ]);
        }
        catch (\Exception $e)
        {
            return $this->_error($e->getMessage());
        }
    }

    public function getProfile($slug)
    {
        
    }

    //---------------------------------------------------------------------------------
    // Halo5 POST
    //---------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function postSeasons()
    {
        $exitCode = \Artisan::call('halo5:season-update');
        
        if ($exitCode == 0)
        {
            return Response::json([
                'error' => false,
                'seasons' => $this->getSeasons()
            ], 200);
        }
        
        return $this->_error('ExitCode was non-zero: ' . $exitCode);
    }

    //---------------------------------------------------------------------------------
    // XPrivate Functions
    //---------------------------------------------------------------------------------

    private function _error($message)
    {
        return Response::json([
            'error' => true,
            'message' => $message
        ], 200);
    }
}