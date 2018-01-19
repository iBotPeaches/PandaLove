<?php

namespace PandaLove\Http\Controllers\Fortnite;

use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Factory as View;
use Onyx\Fortnite\Objects\Stats;
use Onyx\User;
use Onyx\XboxLive\Enums\Console;
use PandaLove\Http\Controllers\Controller;

class ApiV1Controller extends Controller
{
    private $view;
    private $request;
    private $redirect;

    public $inactiveCounter = 10;

    protected $layout = 'layouts.master';

    public function __construct(View $view, Redirect $redirect, Request $request)
    {
        parent::__construct();
        $this->view = $view;
        $this->request = $request;
        $this->redirect = $redirect;
    }

    //---------------------------------------------------------------------------------
    // Fortnite GET
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Fortnite POST
    //---------------------------------------------------------------------------------

    public function postSetup()
    {
        $all = $this->request->all();

        if (isset($all['google_id']) && isset($all['gamertag']) && $all['gamertag'] != '') {
            try {
                $client = new \Onyx\Fortnite\Client();

                /** @var User $user */
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                // With the user, we need gamertag and platform.
                if (!isset($all['platform'])) {
                    $all['platform'] = Console::Xbox;
                } else {
                    $all['platform'] = Console::idFromString($all['platform']);
                }

                // Check if platform exists.
                [$id, $account] = $client->getAccountByTag($all['gamertag'], $all['platform']);

                $msg = 'Account ('.$id.') was found. Added into system. `/bot fn` will work.';
                $client->setPandaAuth($user);
                $client->getAccountRoyaleStats($account, $id);

                return Response::json([
                    'error' => false,
                    'msg'   => $msg,
                ], 200);
            } catch (\Exception $e) {
                return $this->_error($e->getMessage());
            }
        }

        return $this->_error('Missing property: Need google_id, gamertag & platform.');
    }

    public function postUpdate()
    {
        $all = $this->request->all();

        if (isset($all['google_id'])) {
            try {
                $client = new \Onyx\Fortnite\Client();

                /** @var User $user */
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                /** @var Stats $oldStats */
                $oldStats = clone $user->fortnite;

                $client->updateAccount($user->fortnite->account);

                /** @var Stats $newStats */
                $newStats = Stats::where('id', $oldStats->id)->first();

                $msg = \Onyx\Fortnite\Helpers\Bot\MessageGenerator::buildOverwatchUpdateMessage($newStats->account, $oldStats, $newStats);

                return Response::json([
                    'error' => false,
                    'msg'   => $msg,
                ], 200);
            } catch (\Exception $e) {
                return $this->_error($e->getMessage());
            }
        }
    }

    //---------------------------------------------------------------------------------
    // Private Functions
    //---------------------------------------------------------------------------------

    private function _error($message)
    {
        return Response::json([
            'error'   => true,
            'message' => $message,
        ], 200);
    }
}
