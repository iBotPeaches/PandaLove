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
                    @if ($event->isOver())
                        <div class="ui warning message">
                            Sorry. You can't apply to an event that has already happened.
                        </div>
                    @else
                        <p>Currently <strong>{{ $event->count() }}</strong> of <strong> {{ $event->max_players }}</strong> attending.</p>
                        @if ($event->isDestiny())
                            @include('includes.calendar.destiny.attending_table')
                        @elseif ($event->isOverwatch())
                            @include('includes.calendar.overwatch.attending_table')
                        @else
                            @include('includes.calendar.halo5.attending_table')
                        @endif

                        @if ($attendee instanceof \Onyx\Calendar\Objects\Attendee)
                            <div class="ui blue message">
                                You are already attending this event!
                            </div>
                        @else
                            <header>
                                <h3>RSVP</h3>
                            </header>
                            @if (! $event->isFull())
                                @if ($event->isDestiny())
                                    @include('includes.calendar.destiny.select_character')
                                @elseif ($event->isOverwatch())
                                    @include('includes.calendar.overwatch.select_character')
                                @else
                                    @include('includes.calendar.halo5.select_character')
                                @endif
                            @else
                                <div class="ui yellow message">
                                    Uh oh. This game is full.
                                </div>
                            @endif
                        @endif
                    @endif
                    <br />
                    <a href="{{ action('CalendarController@getEvent', [$event->id]) }}">Back to Event</a>
                </div>
            </div>
        </article>
    </div>
@endsection