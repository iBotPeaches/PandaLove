<?php

namespace PandaLove\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return \Response
     */
    public function getIndex()
    {
        return view('home')
            ->with('description', 'PandaLove is a Halo 5 / Destiny clan.');
    }

    public function getRoster()
    {
        return view('roster-switch')
            ->with('description', 'PandaLove rosters for both Destiny & Halo 5.');
    }

    public function getAbout()
    {
        return view('about');
    }
}
