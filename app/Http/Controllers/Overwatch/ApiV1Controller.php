<?php

namespace PandaLove\Http\Controllers\Overwatch;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Factory as View;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Overwatch\Client;
use Onyx\Overwatch\Helpers\Bot\MessageGenerator;
use Onyx\User;
use Onyx\XboxLive\Enums\Console;
use PandaLove\Commands\UpdateOverwatchAccount;
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
        date_default_timezone_set('America/Chicago');
    }

    //---------------------------------------------------------------------------------
    // Overwatch GET
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Overwatch POST
    //---------------------------------------------------------------------------------

    public function postUpdate()
    {
        $all = $this->request->all();

        if (isset($all['google_id']) && isset($all['gamertag']) && $all['gamertag'] == '') {
            try {
                /** @var User $user */
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                if ($user->account_id != 0 && $user->account->mainOverwatchSeason() !== null) {
                    $old = clone $user->account->mainOverwatchSeason();

                    $this->dispatch(new UpdateOverwatchAccount($user->account));

                    $new = $user->account->mainOverwatchSeason();

                    $msg = MessageGenerator::buildOverwatchUpdateMessage($user->account, $old, $new);

                    return Response::json([
                        'error' => false,
                        'msg'   => $msg,
                    ], 200);
                } else {
                    return Response::json([
                        'error' => false,
                        'msg'   => 'bitch pls. You need to confirm your gamertag on PandaLove so I know who you are.',
                    ], 200);
                }
            } catch (ModelNotFoundException $e) {
                return $this->_error('User account could not be found.');
            }
        }

        if (isset($all['gamertag'])) {
            $platform = isset($all['platform']) ? $all['platform'] : Console::Xbox;

            $client = new Client();

            try {
                /** @var Account $account */
                $client->getAccountByTag($all['gamertag'], $platform);

                $account = Account::where('seo', Text::seoGamertag($all['gamertag']))
                    ->with('overwatch')
                    ->where('accountType', $platform)
                    ->first();

                $old = $account->mainOverwatchSeason();
                $new = $account->mainOverwatchSeason();

                $msg = MessageGenerator::buildOverwatchUpdateMessage($account, $old, $new);

                return Response::json([
                    'error' => false,
                    'msg'   => $msg,
                ], 200);
            } catch (\Exception $ex) {
                return $this->_error('Account could not be found - ' . $all['gamertag']);
            }
        }
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
