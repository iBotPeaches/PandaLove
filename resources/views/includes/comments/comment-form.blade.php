{!! Form::open(['action' => ['GameController@postComment', $game->instanceId], 'class' => 'ui reply form', 'id' => 'new-comment-form']) !!}
    <div class="field">
        <textarea name="message" id="message" class="ui small"></textarea>
    </div>
    {!! Form::hidden('game_id', $game->instanceId) !!}
    <div class="ui error message"></div>
    <button class="ui primary submit labeled icon button">
        <i class="icon edit"></i> Add Comment
    </button>
    Posting as <strong>{{ $game->findAccountViaMembershipId($user->account->membershipId)->gamertag }}</strong>
{!! Form::close() !!}

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('#new-comment-form')
                    .form({
                        message: {
                            identifier: 'message',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Please enter a comment'
                                }
                            ]
                        }
                    },
                    {
                        onSuccess: function(event) {
                            $.ajax({
                                type: 'POST',
                                url: "{{ action('GameController@postComment', $game->instanceId) }}",
                                data: $("#new-comment-form").serialize(),
                                success: function(result) {
                                    if (typeof result.flag != 'undefined' && result.flag === true) {
                                        window.location.href = result.url;
                                    } else {
                                        $.each(result, function(index, value) {
                                            $("#new-comment-form")
                                                    .form('add prompt', index)
                                                    .form('add errors', [ value ])
                                            ;
                                        });

                                        $("#new-comment-form .message").show();
                                    }
                                }
                            });

                            return false;
                        }
                    });
            });
    </script>
@append