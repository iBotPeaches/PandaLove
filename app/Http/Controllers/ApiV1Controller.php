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
use PandaLove\Commands\UpdateAccount;

class ApiV1Controller extends Controller {

    private $view;
    private $request;
    private $redirect;

    const MAX_GRIMOIRE = 3800;

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

    public function getLightLeaderboard()
    {
        $pandas = Account::where('clanName', 'Panda Love')
            ->where('clanTag', 'WRKD')
            ->where('inactiveCounter', '<', 10)
            ->get();

        $p = [];

        Hashes::cacheAccountsHashes($pandas);

        foreach($pandas as $panda)
        {
            $character = $panda->highestLevelHighestLight();
            $p[$character->level][] = [
                'name' => $panda->gamertag . " (" . $character->class->title . ")",
                'maxLight' => $character->highest_light,
                'light' => $character->light
            ];
        }

        krsort($p);
        
        foreach ($p as $key => $value)
        {
            // lets sort the sub levels
            usort($value, function($a, $b)
            {
                return $b['maxLight'] - $a['maxLight'];
            });

            $p[$key] = $value;
        }

        $msg = '<strong>Light Leaderboard</strong><br /><br />';

        foreach ($p as $level => $chars)
        {
            $msg .= "<strong>Level " . $level . "'s</strong><br />";

            $i = 1;
            foreach($chars as $char)
            {
                $msg .= $i . ". " . $char['name'] . " <strong>" . $char['maxLight'] . "</strong><br />";
                $i++;
            }

            $msg .= '<br />';
        }

        return Response::json([
            'error' => false,
            'msg' => $msg
        ], 200);
    }

    public function postUpdate()
    {
        $all = $this->request->all();

        if (isset($all['google_id']))
        {
            try
            {
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                if ($user->account_id != 0)
                {
                    $this->dispatch(new UpdateAccount($user->account));

                    return Response::json([
                        'error' => false,
                        'msg' => 'Stats for: <strong>' . $user->account->gamertag . '</strong> have been updated.'
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

    public function postLight()
    {
        $all = $this->request->all();

        if (isset($all['google_id']))
        {
            try
            {
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                if ($user->account_id != 0)
                {
                    $msg = '<strong>' . $user->account->gamertag . '</strong> Light<br /><br />';

                    foreach($user->account->charactersInOrder() as $char)
                    {
                        $msg .= $char->name() . "<br />";
                        $msg .= '<i>Highest Light:</i> ' . $char->highest_light . "<br />";
                        $msg .= '<i>Current Light:</i> ' . $char->light . "<br />";
                    }

                    $msg .= '<br /><br />';
                    $msg .= '<i>Account updated: ' . $user->account->updated_at->diffForHumans() . "</i>";

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

    public function getRaidTuesdayCountdown()
    {
        $raidtuesday = new Carbon('next Tuesday 4 AM','America/Chicago');

        if ($raidtuesday->lt(Carbon::now('America/Chicago')))
        {
            return \Response::json([
                'error' => false,
                'msg' => ''
            ]);
        }
        else
        {
            $countdown = $raidtuesday->diffInSeconds(Carbon::now('America/Chicago'));
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