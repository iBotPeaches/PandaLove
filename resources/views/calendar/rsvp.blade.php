@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1><strong>I want to attend</strong>: {{ $event->title }}</h1>
                        <h3>at {{ $event->humanDate() }}.</h3>
                    </header>
                    <p>Currently <strong>{{ $event->count() }}</strong> attending.</p>
                    @include('includes.calendar.attending_table')
                    @if ($attendee instanceof \Onyx\Destiny\Objects\Attendee)
                        <div class="ui blue message">
                            You are already attending this event!
                        </div>
                    @else
                        <header>
                            <h3>RSVP</h3>
                        </header>
                        @if (! $event->isFull())
                            @include('includes.calendar.select_character')
                        @else
                            <div class="ui yellow message">
                                Uh oh. This game is full.
                            </div>
                        @endif
                    @endif
                    <br />
                    <a href="{{ action('CalendarController@getEvent', [$event->id]) }}">Back to Event</a>
                </div>
            </div>
        </article>
    </div>
@endsection