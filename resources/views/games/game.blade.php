@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="header">
                        @if ($game->isHard)
                            <div class="ui red button fb">Hard</div>
                        @else
                            <div class="ui green button fb">Normal</div>
                        @endif
                        {{ $game->type()->title }}
                    </h1>
                    <div class="ui inverted segment">
                        {{ $game->occurredAt }}. Completed in {{ $game->timeTookInSeconds }}
                    </div>
                    @include('includes.games.game-table')
                </div>
            </div>
        </article>
    </div>
@endsection