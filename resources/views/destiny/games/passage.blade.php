@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <header>
                    <h1>Welcome to another <strong>Trials of Osiris</strong></h1>
                    @if ($passage['stats']['differentMaps'])
                        On the maps: {{ $passage['stats']['maps'] }}
                    @else
                        {{ $games[0]->type()->title }} - {{ $games[0]->type()->description }}.
                    @endif
                    <br />
                </header>
                <div class="12u">
                    <div class="ui top pointing secondary menu">
                        <a class="{{ $gameId == false ? 'active' : null }} item" data-tab="overview">Combined</a>
                        @foreach($games as $index => $game)
                            <a class="item {{ $gameId == $game->instanceId ? 'active' : null }}"
                               data-tab="game_{{ $game->instanceId }}">Game {{ ++$index }}</a>
                        @endforeach
                    </div>
                    <div class="ui bottom attached {{ $gameId == false ? 'active' : null }} tab segment" data-tab="overview">
                        @include('includes.destiny.games.passage-overview')
                    </div>
                    @foreach($games as $game)
                        <div class="ui bottom attached tab segment {{ $gameId == $game->instanceId ? 'active' : null }}" data-tab="game_{{ $game->instanceId }}">
                            @if ($passage['stats']['differentMaps'])
                                <div class="ui segment">
                                    {{ $game->type()->title }} - {{ $game->type()->description }}
                                </div>
                            @endif
                            <div class="ui inverted segment">
                                {{ $game->occurredAt }}. Completed in {{ $game->timeTookInSeconds }}
                            </div>
                            @include('includes.destiny.games.pvp-game-table', ['game' => $game])
                            <a target="_blank" href="https://www.bungie.net/en/Legend/PGCR?instanceId={{ $game->instanceId }}">Bungie.net Game</a>
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