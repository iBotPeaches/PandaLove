<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\Factory as View;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Onyx\Account;
use Onyx\Destiny\Client;
use Onyx\Destiny\Enums\Types;
use Onyx\Destiny\GameNotFoundException;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\User;
use Onyx\XboxLive\Client as XboxClient;
use Carbon\Carbon;

class ApiV1Controller extends Controller {

    private $view;
    private $request;
    private $redirect;

    const MAX_GRIMOIRE = 3620;

    protected $layout = "layouts.master";

    public function __construct(View $view, Redirect $redirect, Request $request)
    {
        parent::__construct();
        $this->view = $view;
        $this->request = $request;
        $this->redirect = $redirect;
    }

    public function getReallevel($gamertag)
    {
        return $this->_error('Taken King removed this variable. Deprecated');
    }

    public function getGrimoire($gamertag)
    {
        try
        {
            $account = Account::with('characters')->where('seo', Text::seoGamertag($gamertag))->firstOrFail();

            $msg = '<strong>' . $account->gamertag . "</strong><br/><br />Grimoire: ";

            $msg .= $account->grimoire;

            if ($account->getOriginal('grimoire') == self::MAX_GRIMOIRE)
            {
                $msg .= "<strong> [MAX]</strong>";
            }

            if ($account->getOriginal('grimoire') < 3000)
            {
                $msg .= "<br /><br /><br />Come on son. Lets get more than 3k";
            }

            return Response::json([
                'error' => false,
                'msg' => $msg
            ], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return $this->_error('Gamertag not found');
        }
    }

    public function getLight($gamertag)
    {
        try
        {
            $account = Account::with('characters')->where('seo', Text::seoGamertag($gamertag))->firstOrFail();

            $msg = '<strong>' . $account->gamertag . " - Light</strong><br /><br />";

            Hashes::cacheAccountHashes($account, null);

            $account->characters->each(function($char) use (&$msg)
            {
                $msg .= $char->name() . ": " . $char->light . "<br />";
            });

            $msg .= '<br /><br />';
            $msg .= '<i>Account updated: ' . $account->updated_at->diffForHumans() . "</i>";

            return Response::json([
                'error' => false,
                'msg' => $msg
            ], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return $this->_error('Gamertag not found');
        }
    }

    public function getLightLeaderboard()
    {
        $pandas = Account::where('clanName', 'Panda Love')
            ->where('clanTag', 'WRKD')
            ->get();

        $p = [];

        Hashes::cacheAccountsHashes($pandas);

        foreach($pandas as $panda)
        {
            $character = $panda->highestLight();
            $p[$panda->gamertag . " (" . $character->level . " " . $character->class->title . ")"] = $character->light;
        }

        arsort($p);
        $msg = '<strong>Light Leaderboard</strong><br /><br />';

        foreach ($p as $key => $value)
        {
            $msg .= $key . " <strong>" . $value . "</strong><br />";
        }

        return Response::json([
            'error' => false,
            'msg' => $msg
        ], 200);
    }

    public function postAddGame()
    {
        $all = $this->request->all();

        if (isset($all['google_id']))
        {
            try
            {
                $user = User::where('google_id', $all['google_id'])
                    ->where('admin', true)
                    ->firstOrFail();

                $client = new Client();

                try
                {
                    $game = $client->fetchGameByInstanceId($all['instanceId']);
                }
                catch (GameNotFoundException $e)
                {
                    return $this->_error('Game could not be found');
                }

                $client->updateTypeOfGame($all['instanceId'], Types::getProperFormat($all['type']), $all['passageId']);

                return Response::json([
                    'error' => false,
                    'msg' => 'Game Added! '
                ], 200);
            }
            catch (ModelNotFoundException $e)
            {
                return $this->_error('User account could not be found.');
            }
        }
    }

    public function getXur()
    {
        $client = new Client();
        $xurData = $client->getXurData();

        if ($xurData == false && strlen($xurData) < 30)
        {
            return $this->_error('XUR is not here right now.');
        }
        else
        {
            return Response::json([
                'error' => false,
                'msg' => $xurData
            ]);
        }
    }

    public function getWhoIsOn()
    {
        $accounts = Account::where('clanName', "Panda Love")->get();

        if (count($accounts) > 0)
        {
            $xboxclient = new XboxClient();
            $presence = $xboxclient->fetchAccountsPresence($accounts);

            $status = $xboxclient->prettifyOnlineStatus($presence, $accounts);
            return Response::json([
                'error' => false,
                'msg' => $status
            ]);
        }
        else
        {
            $this->_error('No Panda Love members were found');
        }
    }

    public function postMakeRSVP()
    {
        $all = $this->request->all();

        if (isset($all['google_id'])) {
            try
            {
                $user = User::where('google_id', $all['google_id'])->firstOrFail();



                return Response::json([
                    'error' => false,
                    'msg' => 'Event created!'
                ], 200);
            }
            catch (ModelNotFoundException $e)
            {
                return $this->_error('User does not have permissions');
            }
        }
    }

    public function getTtkcountdown()
    {
        $release = Carbon::create(2015, 9, 15, 4, 0, 0, 'America/Chicago');

        if ($release->lt(Carbon::now('America/Chicago')))
        {
            return \Response::json([
                'error' => false,
                'msg' => 'Taken King is out. You better be playing.'
            ]);
        }
        else
        {
            $countdown = $release->diffInSeconds(Carbon::now('America/Chicago'));
            $countdown = Text::timeDuration($countdown);

            return \Response::json([
                'error' => false,
                'msg' => $countdown
            ]);
        }
    }

    private function _error($message)
    {
        return Response::json([
            'error' => true,
            'message' => $message
        ], 200);
    }
}