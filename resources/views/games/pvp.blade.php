@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="header">
                        <img class="ui avatar bordered image non-white-bg pvp-emblem" src="{{ $game->type()->extra }}" />
                        {{ $game->pvp->gametype }} <small>({{ $game->type()->title }} - {{ $game->type()->description }})</small>
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
    @if ($user->admin)
        <div class="wrapper style3">
            <h2 class="header">Admin Options</h2>
            @include('includes.games.admin-deletegame')
        </div>
    @endif
@endsection

@section('inline-css')
    <style type="text/css">
        .pvp-emblem {
            background: #9f342f !important;
            height: 1.5em !important;
            width: 1.5em !important;
        }
        small {
            font-size: 0.50em;
        }
    </style>
@append