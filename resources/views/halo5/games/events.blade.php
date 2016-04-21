@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="ui header">
                        {{ $match->playlist->name }} on {{ $match->map->name }} - <a href="{{ action('Halo5\GameController@getGame', [$type, $match->uuid]) }}" class="ui blue button">Go Back</a>
                    </h1>
                    <div class="ui middle aligned divided list">
                        @foreach($match->events as $event)
                            <div class="item">
                                <div class="right floated content">
                                    {{ $event->seconds_since_start }}
                                </div>
                                @include('includes.halo5.game.events.types.' . \Onyx\Halo5\Enums\EventName::getSeo($event->event_name))
                            </div>
                        @endforeach
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