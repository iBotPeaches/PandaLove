@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1><strong>PandaLove</strong>: {{ $event->title }}</h1>
                        <h3>at {{ $event->humanDate() }}. So far </h3>
                    </header>
                    <p>Currently <strong>{{ $event->count() }}</strong> attending {{ $event->isFull() ? 'which makes us full :(' : '.' }}</p>
                    @if (count($event->attendees) == 0)
                        <div class="ui blue message">
                            <strong>Whoa there buddy</strong>
                            <p>
                                No one has RSVP`d for this game yet. There are <strong>{{ $event->max_players }}</strong> spots open.
                                Why don't you <a href="{{ action('CalendarController@getRsvpEvent', [$event->id]) }}">RSVP?</a>
                            </p>
                        </div>
                    @else
                        @include('includes.calendar.attending_table')
                        @if (! $event->isFull())
                            <a href="{{ action('CalendarController@getRsvpEvent', [$event->id]) }}">RSVP To Event</a>
                        @endif
                    @endif
                </div>
            </div>
        </article>
    </div>
@endsection