@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="ui header">
                        {{ $match->playlist->name }} on {{ $match->map->name }} - <a href="{{ action('Halo5\GameController@getGame', [$type, $match->uuid]) }}" class="ui blue button">Go Back</a>
                    </h1>
                    <div class="ui stackable menu">
                        <a class="active item" data-tab="overview">
                            Overview
                        </a>
                        <a class="item" data-tab="text-timeline">
                            Text Timeline
                        </a>
                        <a class="item" data-tab="visual-timeline">
                            Visual Timeline
                        </a>
                        <a class="item" data-tab="gameviewer">
                            GameViewer
                        </a>
                        <a class="item" href="{{ action('Halo5\GameController@getGame', [$type, $match->uuid]) }}">Back to Game</a>
                    </div>
                    <div class="ui bottom attached active tab" data-tab="overview">
                        @include('includes.halo5.game.events.overview-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="text-timeline">
                        @include('includes.halo5.game.events.text-timeline-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="visual-timeline">
                        @include('includes.halo5.game.events.visual-timeline-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="gameviewer">
                        @include('includes.halo5.game.events.viewer-tab')
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-css')
    <style type="text/css">
        .medal {
            zoom:0.45;
            -moz-transform:scale(0.45);
            margin-top: -20px;
            margin-left: -15px;
        }
    </style>
@append

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item').tab();
        });
    </script>
@append