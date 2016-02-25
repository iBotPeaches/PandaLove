<?php namespace PandaLove\Http\Controllers\Xbox;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\Factory as View;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Onyx\Account;
use Onyx\Calendar\Helpers\Event\MessageGenerator;
use Onyx\Calendar\Objects\Event as GameEvent;
use Onyx\User;
use Onyx\XboxLive\Client as XboxClient;
use Carbon\Carbon;
use PandaLove\Http\Controllers\Controller;
use Onyx\XboxLive\Helpers\Bot\MessageGenerator as XboxMessageGenerator;

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

                try {
                    $gameEvent = new GameEvent();
                    $gameEvent->fill($all);
                    $gameEvent->save();
                } catch (\Exception $e) {
                    return $this->_error($e->getMessage());
                }

                // re-set max_players if 0
                if ($gameEvent->max_players == 0)
                {
                    $gameEvent->max_players = $gameEvent->getPlayerDefaultSize();
                    $gameEvent->save();
                }

                $msg = 'This event was created. There are <strong>' .  $gameEvent->max_players . '</strong> spots left. You may apply online <a href="' . \URL::action('CalendarController@getEvent', [$gameEvent->id]) . '">here</a>.';
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
        $accounts = Account::whereHas('destiny', function($query)
        {
            $query->where('clanName', 'Panda Love');
        })->get();

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

    public function getTimezones()
    {
        return Response::json([
            'error' => false,
            'msg' => XboxMessageGenerator::buildTimezonesMessage()
        ]);
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