<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Factory as View;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Onyx\Account;
use Onyx\Destiny\Client;
use Onyx\Destiny\Enums\Types;
use Onyx\Destiny\GameNotFoundException;
use Onyx\Destiny\Helpers\Event\MessageGenerator;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Character;
use Onyx\Destiny\Objects\GameEvent;
use Onyx\User;
use Onyx\XboxLive\Client as XboxClient;
use Carbon\Carbon;
use PandaLove\Commands\UpdateAccount;

class ApiV1Controller extends Controller {

    private $view;
    private $request;
    private $redirect;

    const MAX_GRIMOIRE = 4765; #http://destinytracker.com/destiny/leaderboards/xbox/grimoirescore

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
    // Destiny GET
    //---------------------------------------------------------------------------------

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

    public function getRaidTuesdayCountdown()
    {
        if (Carbon::now('America/Chicago')->isSameDay(new Carbon('Tuesday 4am CST', 'America/Chicago')))
        {
            $raidtuesday = new Carbon('Tuesday 4am CST', 'America/Chicago');
        }
        else
        {
            $raidtuesday = new Carbon('next Tuesday 4 AM','America/Chicago');
        }

        if ($raidtuesday->lt(Carbon::now('America/Chicago')))
        {
            return \Response::json([
                'error' => false,
                'msg' => 'Today is Raid Tuesday! Get your raids in!'
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

    public function getEvents()
    {
        $events = GameEvent::where('start', '>=', Carbon::now('America/Chicago'))
            ->orderBy('start', 'ASC')
            ->get();

        if (count($events) > 0)
        {
            $msg = MessageGenerator::buildEventsResponse($events);

            return Response::json([
                'error' => false,
                'msg' => $msg
            ]);
        }
        else
        {
            return $this->_error('There are no events upcoming.');
        }
    }

    public function getEvent($id)
    {
        try
        {
            $event = GameEvent::where('id', intval($id))->firstOrFail();

            $msg = MessageGenerator::buildSingleEventResponse($event);

            return Response::json([
                'error' => false,
                'msg' => $msg
            ]);
        }
        catch (ModelNotFoundException $e)
        {
            return $this->_error('This game could not be found.');
        }
    }

    //---------------------------------------------------------------------------------
    // Destiny POST
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
                    $msg = '<strong>' . $user->account->gamertag . ' Light</strong> <br /><br />';

                    foreach($user->account->charactersInOrder() as $char)
                    {
                        $msg .= "<strong>" . $char->name() . "</strong><br />";
                        $msg .= '<i>Highest Light:</i> <strong>' . $char->highest_light . "</strong><br />";
                        $msg .= '<i>Current Light:</i> <strong>' . $char->light . "</strong><br /><br />";
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

    public function postAddEvent()
    {
        $all = $this->request->all();

        if (isset($all['google_id']))
        {
            try
            {
                $user = User::where('google_id', $all['google_id'])
                    ->where('admin', true)
                    ->firstOrFail();

                $gameEvent = new GameEvent();
                $gameEvent->fill($all);
                $gameEvent->save();

                // now lets set max_players
                $gameEvent->max_players = $gameEvent->getPlayerDefaultSize($gameEvent->type);
                $gameEvent->save();

                $msg = 'This event was created. There are <strong>' . $gameEvent->max_players . '</strong> spots left. You may apply online <a href="' . \URL::action('CalendarController@getEvent', [$gameEvent->id]) . '">here</a>.';
                $msg .= ' or you can apply via the bot via <strong>/bot rsvp ' . $gameEvent->id . '</strong>';

                return Response::json([
                    'error' => false,
                    'msg' => $msg
                ], 200);
            }
            catch (ModelNotFoundException $e)
            {
                return $this->_error('User does not have permission to make events.');
            }
        }
    }

    public function postRsvp()
    {
        $all = $this->request->all();

        if (isset($all['google_id']))
        {
            try
            {
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                $msg = MessageGenerator::buildRSVPResponse($user, $all);

                return Response::json([
                    'error' => false,
                    'msg' => $msg
                ], 200);
            }
            catch (ModelNotFoundException $e)
            {
                return $this->_error('I do not know who you are. Therefore you cannot RSVP. Sorry.');
            }
        }
    }

    public function postSetup()
    {
        $all = $this->request->all();

        if (isset($all['chat_id']) && isset($all['google_id']))
        {
            try
            {
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                $user->chat_id = $all['chat_id'];
                $user->save();

                return Response::json([
                    'error' => false,
                    'msg' => 'Updated ChatId to <strong>' . $all['chat_id'] . '</strong>. I will PM you here for alerts.'
                ], 200);
            }
            catch (ModelNotFoundException $e)
            {
                return $this->_error('I do not know who you are. Therefore I cannot set your chat ID.');
            }
        }
        else
        {
            return $this->_error('Chat/Google ID not found.');
        }
    }

    //---------------------------------------------------------------------------------
    // Xbox GET
    //---------------------------------------------------------------------------------

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