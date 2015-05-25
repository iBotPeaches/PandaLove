@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="ui header">
                        @if (! $game->isPoe())
                            @if ($game->isHard)
                                <div class="ui red button fb">Hard</div>
                            @else
                                <div class="ui green button fb">Normal</div>
                            @endif
                        @else
                            <div class="ui purple button fb">Level {{ $game->type()->extraThird }}</div>
                        @endif
                        {{ $game->type()->title }}
                    </h1>
                    @if ($game->isPoe())
                        <small class="no-margin">{{ $game->type()->description }}</small>
                    @endif
                    <div class="ui inverted segment">
                        {{ $game->occurredAt }}. Completed in {{ $game->timeTookInSeconds }}
                    </div>
                    @include('includes.games.game-table')
                    <a target="_blank" href="https://www.bungie.net/en/Legend/PGCR?instanceId={{ $game->instanceId }}">Bungie.net Game</a>
                </div>
            </div>
        </article>
        @include('includes.comments.view')
    </div>
    @if (isset($user) && $user->admin)
        <div class="wrapper style3">
            <h2 class="header">Admin Options</h2>
            @include('includes.games.admin-deletegame')
        </div>
    @endif
@endsection

@section('inline-css')
    <style type="text/css">
        .h1 {
            margin-bottom: -0.75em !important;
        }
    </style>
@append