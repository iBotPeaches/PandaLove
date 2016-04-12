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
                                <img class="ui avatar image" src="{{ $event->killer->h5_emblem->getEmblem() }}">
                                <div class="content">
                                    <a href="{{ action('Halo5\ProfileController@index', [$event->killer->seo]) }}">{{ $event->killer->gamertag }}</a>
                                    killed
                                    @if (isset($event->victim) && $event->victim != null)
                                        <a href="{{ action('Halo5\ProfileController@index', [$event->victim->seo]) }}">{{ $event->victim->gamertag }}</a>
                                    @else
                                        <a href="#">{{ $event->victim_enemy->name or "Unknown Enemy" }}</a>
                                    @endif
                                    with a <b>{{ $event->killer_weapon->name }}</b>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-css')
@append