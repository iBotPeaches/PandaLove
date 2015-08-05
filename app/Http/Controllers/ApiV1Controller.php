<?php namespace PandaLove\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\Factory as View;
use Illuminate\Http\Request as Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Support\Facades\Response;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;

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

            if ($account->getOriginal(['grimoire']) < 3000)
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

    private function _error($message)
    {
        return Response::json([
            'error' => true,
            'message' => $message
        ], 200);
    }
}