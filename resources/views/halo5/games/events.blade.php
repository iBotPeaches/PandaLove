@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="ui header">
                        {{ $match->playlist->name }} ({{ $match->gametype->name }}) on {{ $match->mapVariant->name }} ({{ $match->map->name }})
                    </h1>
                    <div class="ui stackable menu">
                        <a class="active item" data-tab="overview">
                            Overview
                        </a>
                        <a class="item" data-tab="charts">
                            Charts
                        </a>
                        <a class="item" data-tab="timeline">
                            Timeline
                        </a>
                        @if ($match->hasRounds())
                            <a class="item" data-tab="rounds">
                                Rounds
                            </a>
                        @endif
                        <a class="item" data-tab="gameviewer">
                            Viewer
                        </a>
                        <a class="item" href="{{ action('Halo5\GameController@getGame', [$type, $match->uuid]) }}">Back to Game</a>
                    </div>
                    <div class="ui bottom attached active tab" data-tab="overview">
                        @include('includes.halo5.game.events.overview-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="charts">
                        @include('includes.halo5.game.events.charts-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="timeline">
                        @include('includes.halo5.game.events.timeline-tab')
                    </div>
                    @if ($match->hasRounds())
                        <div class="ui bottom attached tab" data-tab="rounds">
                            @include('includes.halo5.game.events.round-select-tab', ['data' => $round_data])
                        </div>
                    @endif
                    <div class="ui bottom attached tab" data-tab="gameviewer">
                        @include('includes.halo5.game.events.viewer-tab')
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-css')
    <link rel="stylesheet" href="{{ asset('css/vertical-timeline.css') }}" />
    <style type="text/css">
        .medal {
            -moz-transform: scale(0.5,0.5);
            -ms-transform: scale(0.5,0.5);
            -webkit-transform: scale(0.5,0.5);
            -o-transform: scale(0.5,0.5);
            transform: scale(0.5,0.5);
            margin-top: -20px;
            margin-left: -15px;
        }
    </style>
@append

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item').tab();

            var my_posts = $("[rel=tooltip]");

            var size = $(window).width();
            for (i = 0; i <my_posts.length; i++) {
                the_post = $(my_posts[i]);

                if (the_post.hasClass('invert') && size >= 767) {
                    the_post.popup({ placement: 'left'});
                    the_post.css("cursor", "pointer");
                } else {
                    the_post.popup({ placement: 'right'});
                    the_post.css("cursor", "pointer");
                }
            }
        });
    </script>
@append