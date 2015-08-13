<?php namespace PandaLove\Http\Controllers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Onyx\Destiny\Objects\Attendee;
use Onyx\Destiny\Objects\GameEvent;
use PandaLove\Http\Requests\AddRSVP;

class CalendarController extends Controller {

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth.panda');
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

            $attendee = Attendee::where('membershipId', $this->user->account->membershipId)
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

    public function postRsvpEvent(AddRSVP $request)
    {
        $game_id = $request->get('game_id');

        try
        {
            $event = GameEvent::where('id', intval($game_id))->firstOrFail();

            $attendee = new Attendee();
            $attendee->game_id = $event->id;
            $attendee->membershipId = $this->user->account->membershipId;
            $attendee->characterId = $request->get('character');
            $attendee->account_id = $this->user->account->id;
            $attendee->user_id = $this->user->id;
            $attendee->save();

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