@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="header">
                        <img class="ui avatar bordered image non-white-bg pvp-emblem" src="{{ $game->type()->extra }}" />
                        {{ $game->type()->title }}
                        <div class="ui red button fb">{{ $game->gametype }}</div>
                    </h1>
                    <div class="ui inverted segment">
                        {{ $game->occurredAt }}. Completed in {{ $game->timeTookInSeconds }}
                    </div>
                    @include('includes.games.pvp-game-table')
                </div>
            </div>
        </article>
        @include('includes.comments.view')
    </div>
@endsection

@section('inline-css')
    <style type="text/css">
        .pvp-emblem {
            background: #9f342f !important;
            height: 1.5em !important;
            width: 1.5em !important;
        }
    </style>
@append