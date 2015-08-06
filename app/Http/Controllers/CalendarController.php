<?php namespace PandaLove\Http\Controllers;


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
}