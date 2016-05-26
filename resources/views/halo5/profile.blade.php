@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Hi. I am <strong>{{ $account->gamertag }}</strong></h1>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="3u">
                    <div class="ui fluid card">
                        <div class="image">
                            <img src="{{ $account->h5->getSpartan() }}" />
                        </div>
                        <div class="content">
                            <div class="left floated author">
                                <img class="ui avatar image" src="{{ $account->h5->getEmblem() }}" />
                            </div>
                            <div class="right floated">
                                <span class="header">Level {{ $account->h5->spartanRank }} Spartan</span>
                            </div>
                        </div>
                    </div>
                    @if (isset($user) && $user != null)
                        <div class="ui raised segment" id="manual-refresh-button" style="display:none;">
                            <a href="{{ action('Halo5\ProfileController@manualUpdate', [$account->seo]) }}" class="ui fluid green button">Refresh Data</a>
                        </div>
                    @endif
                </div>
                <div class="9u">
                    <div class="ui stackable container menu">
                        <a class="active item" data-tab="overview">
                            Overview
                        </a>
                        <a class="item" data-tab="arena">
                            Arena
                        </a>
                        <a class="item" data-tab="warzone">
                            Warzone
                        </a>
                        <a class="item" data-tab="recent">
                            Recent Games
                        </a>
                    </div>
                    <div class="ui bottom attached active tab" data-tab="overview">
                        @define $playlist = $account->h5->record_playlist()

                        @if ($playlist == null)
                            <div class="ui warning message">
                                Uh oh. No Arena data found. This user hasn't played any Ranked Arena.
                            </div>
                        @else
                            @include('includes.halo5.profile.overview-tab')
                        @endif
                    </div>
                    <div class="ui bottom attached tab" data-tab="arena">
                        @include('includes.halo5.profile.arena-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="warzone">
                        @include('includes.halo5.profile.warzone-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="recent">
                        <div id="recent-tab-content">
                            <div class="ui info message">
                                Uh oh. You should not be seeing this. Wait like 10 more seconds. This means the loading for the Recent Games failed.
                                A refresh should hopefully fix this.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item').tab();

            $('.medal')
                    .popup({
                        inline   : false,
                        hoverable: true
                    })
            ;

            $('.rank-placement')
                    .progress({
                        label: 'ratio',
                        text: {
                            ratio: '{value} of {total}'
                        }
                    })
            ;

            $('.percent-next')
                    .progress({
                    })
            ;
        });
    </script>
@append

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $.ajax({
                url: '{{ URL::action('Halo5\ProfileController@checkForUpdate', array($account->gamertag)) }}',
                success: function(result) {
                    $msg = $("#update-message");
                    if (result.updated && result.frozen == false) {
                        $msg.removeClass('icon').addClass('green');
                        $("#update-message .content p").empty().text("Account Updated! Refresh for new data");
                    } else if (result.updated == false && result.frozen == false) {
                        $msg.removeClass('icon').addClass('blue');
                        $("#update-message .content p").empty().text("Account last updated: " + result.last_update);
                        $("#manual-refresh-button").show();
                    } else if (result.frozen) {
                        $msg.removeClass('icon').addClass('yellow');
                        $("#update-message .content p").empty().html(result.last_update);
                    }

                    $("#update-message i").remove();
                    $("#update-message .header").remove();
                }
            });
            change_page(0);

            $("body").on('click', '.recent-pagination', function(event) {
                $(this).addClass("disabled");
                change_page($(this).data("page"));
            });
        });

        function change_page(number) {
            $.ajax({
                url: '{{ URL::action('Halo5\ProfileController@getRecentGames', array($account->gamertag)) }}/' + number,
                success: function(result) {
                    $("#recent-tab-content").html(result);

                    $('.special.cards .image').dimmer({
                        on: 'hover'
                    });

                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }
            });
        }
    </script>
@append

@section('inline-css')
    <style type="text/css">
        .stat-fix {
            margin-bottom: -13px;
        }
    </style>
@append