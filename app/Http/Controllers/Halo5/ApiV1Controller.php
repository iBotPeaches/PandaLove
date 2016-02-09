<?php namespace PandaLove\Http\Controllers\Halo5;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\Factory as View;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Onyx\Halo5\Helpers\Bot\MessageGenerator;
use Onyx\Halo5\Objects\Data;
use Onyx\User;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Controllers\Controller;

class ApiV1Controller extends Controller {

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

    public function getArenaLeaderboard()
    {
        // Get all halo accounts
        $accounts = Account::with('destiny', 'h5.playlists')
            ->whereHas('destiny', function($query)
            {
                $query->where('clanName', 'Panda Love');
            })
            ->whereHas('h5', function($query)
            {
                $query->where('totalKills', '!=', 0);
            })
            ->orderBy('gamertag', 'ASC')
            ->get();
            
        // Make the collection
        $scores = Collection::make([]);
        foreach ($accounts as $account)
        {
            $score = log($account->h5->totalGames) * $account->h5->kd(false);

            // Mathemetically improbable any will ever have the same score
            $scores.put($score, $account->gamertag);
        }
        
        // Sort
        $scores = $scores->sortByDesc('score');
        
        // Get the message
        $msg = MessageGenerator::buildArenaLeaderboardMessage($scores);
        
        return Response::json([
            'error' => false,
            'msg' => $msg
        ], 200);
    }
        

    //---------------------------------------------------------------------------------
    // Halo5 POST
    //---------------------------------------------------------------------------------

    public function postUpdate()
    {
        $all = $this->request->all();

        if (isset($all['google_id']))
        {
            try
            {
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                if ($user->account_id != 0 && $user->account->h5 instanceof Data)
                {
                    $old_h5 = clone $user->account->h5;
                    $old_warzone = clone $user->account->h5->warzone;

                    $this->dispatch(new UpdateHalo5Account($user->account));

                    $new_h5 = Data::where('account_id', $user->account_id)->first();

                    $msg = MessageGenerator::buildH5UpdateMessage($user->account, $old_h5, $old_warzone, $new_h5);

                    return Response::json([
                        'error' => false,
                        'msg' => $msg
                    ], 200);
                }
                else
                {
                    return Response::json([
                        'error' => false,
                        'msg' => 'bitch pls. You need to confirm your gamertag on PandaLove so I know who you are.'
                    ], 200);
                }
            }
            catch (ModelNotFoundException $e)
            {
                return $this->_error('User account could not be found.');
            }
        }
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
