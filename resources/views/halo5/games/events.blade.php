@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="ui header">
                        [FUTURE] EVENTS DETAILS OF MATCH ____________-
                    </h1>
                    <div class="ui feed">
                        @foreach($match->events as $event)
                            <div class="event">
                                <div class="label">
                                    <img src="{{ $event->getRelation('killer')->h5->getEmblem() }}" />
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        <a class="user">{{ $event->getRelation('killer')->gamertag }}</a>
                                        killed <a class="user">{{ $event->getRelation('victim')->gamertag }}</a>
                                        with a <a class="user">{{ $event->getRelation('killer_weapon')->name }}</a>
                                        <div class="date">
                                            {{ $event->seconds_since_start }}
                                        </div>
                                    </div>
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