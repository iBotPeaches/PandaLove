<article class="container no-image" id="top">
    <div class="row">
        <div class="12u">
            <h2 class="header">Comments</h2>
            @if (count($game->comments) == 0)
                <div class="ui info message">
                    No comments yet. Why don't you get us started with one?
                </div>
                @if (isset($user) && $user->account_id != 0)
                    @include('includes.comments.comment-form')
                @endif
            @else
                <div class="ui threaded comments">
                    @foreach($game->comments as $comment)
                        <div class="comment">
                            <a href="{{ action('Destiny\ProfileController@index', $comment->account->seo) }}" class="avatar">
                                <img class="ui rounded" src="{{ $comment->emblem() }}" />
                            </a>
                            <div class="content">
                                <a href="{{ action('Destiny\ProfileController@index', $comment->account->seo) }}" class="author">{{ $comment->account->gamertag }}</a>
                                <div class="metadata">
                                    <span class="date">{{ $comment->created_at }}</span>
                                </div>
                                <div class="text">
                                    {{ $comment->comment }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if (isset($user) && $user->account_id != 0)
                    @include('includes.comments.comment-form')
                @endif
            @endif
        </div>
    </div>
</article>