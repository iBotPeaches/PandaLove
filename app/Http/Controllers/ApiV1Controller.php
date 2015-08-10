<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\Factory as View;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Onyx\Account;
use Onyx\Destiny\Client;
use Onyx\Destiny\Enums\Types;
use Onyx\Destiny\GameNotFoundException;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\User;
use Onyx\XboxLive\Client as XboxClient;

class ApiV1Controller extends Controller {

    private $view;
    private $request;
    private $redirect;

    const MAX_GRIMOIRE = 3620;

    protected $layout = "layouts.master";

    public function __construct(View $view, Redirect $redirect, Request $request)
    {
        parent::__construct();
        $this->view = $view;
        $this->request = $request;
        $this->redirect = $redirect;
    }

    public function getReallevel($gamertag)
    {
        try
        {
            $account = Account::with('characters')->where('seo', Text::seoGamertag($gamertag))->firstOrFail();

            $msg = '<strong>' . $account->gamertag . "</strong><br/><br />";

            // attempt hash cache
            Hashes::cacheAccountHashes($account, null);

            $account->characters->each(function($char) use (&$msg)
            {
               $msg .= $char->name() . ": " . $char->realLevel . "<br />";
            });

            return Response::json([
                'error' => false,
                'msg' => $msg
            ], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return $this->_error('Gamertag not found');
        }
    }

    public function getGrimoire($gamertag)
    {
        try
        {
            $account = Account::with('characters')->where('seo', Text::seoGamertag($gamertag))->firstOrFail();

            $msg = '<strong>' . $account->gamertag . "</strong><br/><br />Grimoire: ";

            $msg .= $account->grimoire;

            if ($account->getOriginal('grimoire') == self::MAX_GRIMOIRE)
            {
                $msg .= "<strong> [MAX]</strong>";
            }

            if ($account->getOriginal('grimoire') < 3000)
            {
                $msg .= "<br /><br /><br />Come on son. Lets get more than 3k";
            }

            return Response::json([
                'error' => false,
                'msg' => $msg
            ], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return $this->_error('Gamertag not found');
        }
    }

    public function postAddGame()
    {
        $all = $this->request->all();

        if (isset($all['google_id']))
        {
            try
            {
                $user = User::where('google_id', $all['google_id'])
                    ->where('admin', true)
                    ->firstOrFail();

                $client = new Client();

                try
                {
                    $game = $client->fetchGameByInstanceId($all['instanceId']);
                }
                catch (GameNotFoundException $e)
                {
                    return $this->_error('Game could not be found');
                }

                $client->updateTypeOfGame($all['instanceId'], Types::getProperFormat($all['type']), $all['passageId']);

                return Response::json([
                    'error' => false,
                    'msg' => 'Game Added! '
                ], 200);
            }
            catch (ModelNotFoundException $e)
            {
                return $this->_error('User account could not be found.');
            }
        }
    }

    public function getXur()
    {
        $client = new Client();
        $xurData = $client->getXurData();

        if ($xurData == false && strlen($xurData) < 30)
        {
            return $this->_error('XUR is not here right now.');
        }
        else
        {
            return Response::json([
                'error' => false,
                'msg' => $xurData
            ]);
        }
    }

    public function getWhoIsOn()
    {
        $accounts = Account::where('clanName', "Panda Love")->get();

        if (count($accounts) > 0)
        {
            $xboxclient = new XboxClient();
            $presence = $xboxclient->fetchAccountsPresence($accounts);

            $user_string = '<strong>Online Status</strong><br/>';
            foreach ($presence as $seo => $response)
            {
                $data = json_decode($response->getBody(), true);

                if ($data['state'] == "Online")
                {
                    foreach ($data['devices'] as $device)
                    {
                        if ($device['type'] == "XboxOne")
                        {
                            foreach ($device['titles'] as $title)
                            {
                                if ($title['name'] == "Destiny")
                                {
                                    $gt = $accounts->where('seo', $seo)->first();
                                    $user_string .= "<strong>" . $gt->gamertag . ": </strong>" . $title['name'] . "<br/>";
                                }
                            }
                        }
                    }
                }
            }
            return Response::json([
                'error' => false,
                'msg' => $user_string
            ]);
        }
        else
        {
            $this->_error('No Panda Love members were found');
        }
    }

    private function _error($message)
    {
        return Response::json([
            'error' => true,
            'message' => $message
        ], 200);
    }
}