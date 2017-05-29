<?php

namespace PandaLove\Http\Controllers;

use Illuminate\Http\Request;
use Onyx\Destiny\Objects\Game;
use Onyx\Objects\Comment;
use PandaLove\Http\Requests\AddCommentRequest;

class CommentController extends Controller
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

    //---------------------------------------------------------------------------------
    // Comment GET
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Comment POST
    //---------------------------------------------------------------------------------

    public function postComment(AddCommentRequest $request)
    {
        $game = Game::where('instanceId', $request->get('game_id'))->first();
        $membershipId = $this->user->account->destiny->membershipId;

        $comment = new Comment();
        $comment->comment = $request->get('message');
        $comment->destiny_membershipId = $membershipId;
        $comment->destiny_characterId = $game->findAccountViaMembershipId($membershipId, false);
        $comment->parent_comment_id = 0;
        $comment->account_id = $this->user->id;

        $game->comments()->save($comment);

        return response()->json(['flag' => true, 'url' => \URL::action('Destiny\GameController@getGame', $game->instanceId)]);
    }
}
