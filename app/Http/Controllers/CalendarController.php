<?php namespace PandaLove\Http\Controllers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Onyx\Destiny\Objects\GameEvent;

class CalendarController extends Controller {

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
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
        });

        return $events->toJson();
    }

    public function getEvent($id)
    {
        try
        {
            $event = GameEvent::where('id', intval($id))->firstOrFail();

            return view('calendar/event')
                ->with('description', 'TODO: Title')
                ->with('event', $event);
        }
        catch (ModelNotFoundException $e)
        {
            app()->abort(404, 'Event could not be found.');
        }
    }
}