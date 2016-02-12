<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Onyx\Calendar\Objects\Attendee;
use Onyx\Calendar\Objects\Event as GameEvent;
use Onyx\Hangouts\Helpers\Messages;
use PandaLove\Http\Requests\AddRSVP;
use PandaLove\Http\Requests\deleteEventRequest;

class CalendarController extends Controller {

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth.panda');
        date_default_timezone_set('America/Chicago');
    }

    /**
     * Show the application calendar to the user.
     *
     * @return \Response
     */
    public function getIndex()
    {
        return view('calendar/index')
            ->with('description', 'PandaLove\'s Calendar of Events');
    }

    public function getEvents(Request $request)
    {
        $events = GameEvent::whereBetween('start', [$request->get('start'), $request->get('end')])->get();

        $events->each(function($event)
        {
            $event->url = action('CalendarController@getEvent', [$event->id]);
            $event->backgroundColor = $event->getBackgroundColor();
        });

        return $events->toJson();
    }

    public function getEvent($id)
    {
        try
        {
            $event = GameEvent::with('attendees')
                ->where('id', intval($id))->firstOrFail();

            return view('calendar/event')
                ->with('description', 'PandaLove:' . $event->title)
                ->with('event', $event);
        }
        catch (ModelNotFoundException $e)
        {
            app()->abort(404, 'Event could not be found.');
        }
    }

    public function getRsvpEvent($id)
    {
        try
        {
            $event = GameEvent::with('attendees')
                ->where('id', intval($id))->firstOrFail();

            $attendee = Attendee::where('account_id', $this->user->account->id)
                ->where('game_id', $event->id)
                ->first();

            return view('calendar/rsvp')
                ->with('description', 'RSVP: ' . $event->title)
                ->with('event', $event)
                ->with('attendee', $attendee);
        }
        catch (ModelNotFoundException $e)
        {
            app()->abort(404, 'Event could not be found.');
        }
    }

    public function getCancelEvent($id)
    {
        try
        {
            $event = GameEvent::where('id', intval($id))->firstOrFail();
            Attendee::where('game_id', $event->id)
                ->where('user_id', $this->user->id)
                ->delete();

            return \Redirect::action('CalendarController@getEvent', [$event->id])
                ->with('flash_message', [
                    'type' => 'success',
                    'header' => 'You have cancelled your RSVP.'
                ]);
        }
        catch (ModelNotFoundException $e)
        {
            app()->abort(404, 'Event could not be found.');
        }
    }

    public function deleteEvent(deleteEventRequest $request)
    {
        $event = GameEvent::where('id', $request->get('event_id'))->first();
        $event->delete();

        return \Redirect::to('/calendar')
            ->with('flash_message', [
                'type' => 'green',
                'header' => 'Event Deleted!',
                'close' => true,
                'body' => 'You deleted event (' . $request->get('event_id') . ") "
            ]);
    }

    public function postRsvpEvent(AddRSVP $request)
    {
        $game_id = $request->get('game_id');

        try
        {
            $event = GameEvent::where('id', intval($game_id))->firstOrFail();

            $attendee = new Attendee();
            $attendee->game_id = $event->id;

            if ($event->isDestiny())
            {
                $attendee->membershipId = $this->user->account->destiny_membershipId;
                $attendee->characterId = $request->get('character', 0);
            }

            $attendee->account_id = $this->user->account->id;
            $attendee->user_id = $this->user->id;
            $attendee->save();

            // hit bot
            if (app()->environment() == "production")
            {
                $messenger = new Messages();
                $messenger->sendGroupMessage('<strong>' . $this->user->account->gamertag . '</strong> confirmed RSVP to event: <strong>' . $event->title . '</strong>');
            }

            return \Redirect::action('CalendarController@getEvent', [$event->id])
                ->with('flash_message', [
                    'type' => 'success',
                    'header' => 'RSVP Confirmed!'
                ]);
        }
        catch(ModelNotFoundException $e)
        {
            return false;
        }
    }
}