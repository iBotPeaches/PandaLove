@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <header>
                    <h1>Welcome to another <strong>Raid Tuesday</strong></h1>
                </header>
                <div class="12u">
                    <div class="ui top attached tabular menu">
                        <a class="{{ $gameId == false ? 'active' : null }} item" data-tab="overview">Combined</a>
                        @foreach($games as $game)
                            <a class="{{ $gameId == $game->instanceId ? 'active' : null }} item"
                               data-tab="game_{{ $game->instanceId }}">{{ ($game->isHard ? '[Hard]' : '[Normal]') . " " . $game->type()->title }}</a>
                        @endforeach
                    </div>
                    <div class="ui bottom attached tab segment {{ $gameId == false ? 'active' : null }}" data-tab="overview">
                        @include('includes.destiny.games.tuesday-overview')
                    </div>
                    @foreach($games as $game)
                        <div class="ui bottom attached tab segment {{ $gameId == $game->instanceId ? 'active' : null }}" data-tab="game_{{ $game->instanceId }}">
                            <div class="ui inverted segment">
                                {{ $game->occurredAt }}. Completed in {{ $game->timeTookInSeconds }}
                            </div>
                            @include('includes.destiny.games.game-table', ['game' => $game])
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