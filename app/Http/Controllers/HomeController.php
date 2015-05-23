<?php namespace PandaLove\Http\Controllers;


class HomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
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
            ->with('description', 'PandaLove is a Destiny clan focused on PVP, Raids, Prison of Elders and Trials of Osiris.');
	}
}
