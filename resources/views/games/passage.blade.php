@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <header>
                    <h1>Welcome to another <strong>Trials of Osiris</strong></h1>
                    <small>All games here result in a trip to the Lighthouse (9-0).</small>
                </header>
                <div class="12u">
                    <div class="ui top pointing secondary menu">
                        <a class="active item" data-tab="overview">Combined</a>
                        @foreach($games as $index => $game)
                            <a class="item" data-tab="game_{{ $game->instanceId }}">Game {{ ++$index }}</a>
                        @endforeach
                    </div>
                    <div class="ui bottom attached active tab segment" data-tab="overview">
                        @include('includes.games.passage-overview')
                    </div>
                    @foreach($games as $game)
                        <div class="ui bottom attached tab segment" data-tab="game_{{ $game->instanceId }}">
                            <div class="ui inverted segment">
                                {{ $game->occurredAt }}. Completed in {{ $game->timeTookInSeconds }}
                            </div>
                            @include('includes.games.pvp-game-table', ['game' => $game])
                        </div>
                    @endforeach
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item')
                    .tab()
            ;
        });
    </script>
@append

@section('inline-css')
    <style type="text/css">
        header {
            margin: 0 0 0 0 !important;
        }
    </style>
@append