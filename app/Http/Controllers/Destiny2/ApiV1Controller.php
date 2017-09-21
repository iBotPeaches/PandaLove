<?php

namespace PandaLove\Http\Controllers\Destiny2;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Factory as View;
use Onyx\Account;
use Onyx\Destiny2\Objects\Character;
use Onyx\User;
use PandaLove\Commands\UpdateDestiny2Account;
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
    // Destiny2 GET
    //---------------------------------------------------------------------------------

    public function getLightLeaderboard()
    {
        /** @var Account[] $pandas */
        $pandas = Account::with('user', 'destiny2.character1', 'destiny2.character2', 'destiny2.character3')
            ->whereHas('user', function ($query) {
                $query->where('isPanda', true);
            })
            ->whereHas('destiny2', function ($query) {
                $query->where('inactiveCounter', '<=', 10);
            })
            ->orderBy('gamertag', 'ASC')
            ->limit(15)
            ->get();

        $p = [];
        foreach ($pandas as $panda) {
            foreach ($panda->destiny2->characters() as $character) {
                if (!$character instanceof Character) {
                    continue;
                }
                if ($character->max_light > 240) {
                    $p[$character->max_light][] = [
                        'name'     => $panda->gamertag.' ('.$character->name().')',
                        'maxLight' => $character->max_light,
                        'light'    => $character->light,
                    ];
                }
            }
        }

        krsort($p);

        $msg = '<strong>Power Leaderboard</strong><br /><br />';

        foreach ($p as $level => $chars) {
            $msg .= '<strong>Power Level '.$level."'s</strong><br />";

            $i = 1;
            foreach ($chars as $char) {
                $msg .= $i.'. '.$char['name'].'<br />';
                $i++;
            }

            $msg .= '<br />';
        }

        return Response::json([
            'error' => false,
            'msg'   => $msg,
        ], 200);
    }

    public function getXur()
    {
        return $this->_error('XUR endpoint is not ready yet.');
    }

    //---------------------------------------------------------------------------------
    // Destiny2 POST
    //---------------------------------------------------------------------------------

    public function postUpdate()
    {
        $all = $this->request->all();

        if (isset($all['google_id'])) {
            try {

                /** @var User $user */
                $user = User::where('google_id', $all['google_id'])
                    ->firstOrFail();

                if ($user->account_id != 0) {
                    $this->dispatch(new UpdateDestiny2Account($user->account));

                    $msg = 'Stats for: <strong>'.$user->account->gamertag.'</strong> have been updated. <br /><br />';
                    foreach ($user->account->destiny2->characters() as $character) {
                        $msg .= $character->name().' - '.$character->max_light.'<br />';
                    }

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
