@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Hi. I am <strong>{{ $account->gamertag }}</strong></h1>
                    </header>
                    @include('includes.profile.character-tab')
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $.ajax({
                url: '{{ URL::action('ProfileController@checkForUpdate', array($account->gamertag)) }}',
                success: function(result) {
                    $msg = $("#update-message");
                    if (result.updated) {
                        $msg.removeClass('icon').addClass('green');
                        $("#update-message .content p").empty().text("Account Updated! Refresh for new data");
                    } else {
                        $msg.removeClass('icon').addClass('blue');
                        $("#update-message .content p").empty().text("Account last updated: " + result.last_update);
                    }

                    $("#update-message i").remove();
                    $("#update-message .header").remove();
                }
            });
        })
    </script>
@append