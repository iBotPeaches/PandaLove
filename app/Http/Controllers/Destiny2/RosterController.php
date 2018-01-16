<?php

namespace PandaLove\Http\Controllers\Destiny2;

use Illuminate\Http\Request;
use PandaLove\Http\Controllers\Controller;

class RosterController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function getIndex()
    {
        return view('destiny2.roster');
    }
}
