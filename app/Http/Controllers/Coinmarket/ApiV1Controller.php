<?php

namespace PandaLove\Http\Controllers\Coinmarket;

use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Factory as View;
use Onyx\Coinmarket\Client;
use Onyx\Coinmarket\Helpers\Bot\MessageGenerator;
use PandaLove\Http\Controllers\Controller;

class ApiV1Controller extends Controller
{
    private $view;
    private $request;
    private $redirect;

    protected $layout = 'layouts.master';

    public function __construct(View $view, Redirect $redirect, Request $request)
    {
        parent::__construct();
        $this->view = $view;
        $this->request = $request;
        $this->redirect = $redirect;
    }

    //---------------------------------------------------------------------------------
    // Destiny GET
    //---------------------------------------------------------------------------------

    public function getSymbol($name)
    {
        $client = new Client();
        $data = $client->getTicker($name);

        if (empty($data)) {
            return $this->_error('Could not find symbol: ' . $name);
        }

        return Response::json([
            'error' => false,
            'message' => MessageGenerator::generateTickerMessage($data)
        ], 200);
    }

    //---------------------------------------------------------------------------------
    // XPrivate Functions
    //---------------------------------------------------------------------------------

    private function _error($message)
    {
        return Response::json([
            'error'   => true,
            'message' => $message,
        ], 200);
    }
}
