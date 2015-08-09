<?php namespace PandaLove\Http\Controllers;


use Illuminate\Http\Request;
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
        return view('calendar')
            ->with('description', 'PandaLove\'s Calendar of Events');
    }

    public function getEvents(Request $request)
    {
        $events = GameEvent::whereBetween('start', [$request->get('start'), $request->get('end')])->get();
        return $events->toJson();
    }
}