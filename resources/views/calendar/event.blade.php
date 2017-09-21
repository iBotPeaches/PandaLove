<?
/** @var $event \Onyx\Calendar\Objects\Event */
?>
@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1><strong>{{ $event->game_name() }}</strong> - {{ $event->title }}</h1>
                        <h3>at {{ $event->humanDate() }}. So far </h3>
                    </header>
                    <p><strong>{{ $event->type }}</strong>. Currently <strong>{{ $event->count() }}</strong> of <strong> {{ $event->max_players }}</strong> attending.</p>
                    @if (count($event->attendees) == 0)
                        <div class="ui blue message">
                            <strong>Whoa there buddy</strong>
                            @if ($event->isOver())
                                <p>
                                    This event has passed. Check back for an upcoming event at our <a href="{{ URL::action('CalendarController@getIndex') }}">calendar.</a>
                                </p>
                            @else
                                <p>
                                    No one has RSVP`d for this game yet. There are <strong>{{ $event->max_players }}</strong> spots open.
                                    Why don't you <a href="{{ action('CalendarController@getRsvpEvent', [$event->id]) }}">RSVP?</a>
                                </p>
                            @endif
                        </div>
                    @else
                        @if ($event->isDestiny())
                            @include('includes.calendar.destiny.attending_table')
                        @elseif ($event->isOverwatch())
                            @include('includes.calendar.overwatch.attending_table')
                        @elseif ($event->isDestiny2())
                            @include('includes.calendar.destiny2.attending_table')
                        @else
                            @include('includes.calendar.halo5.attending_table')
                        @endif

                        @if (! $event->isFull() && ! $event->isAttending($user))
                            <a href="{{ action('CalendarController@getRsvpEvent', [$event->id]) }}">RSVP To Event</a>
                        @endif
                    @endif
                </div>
            </div>
        </article>
        @if (isset($user) && $user->admin)
            <div class="wrapper style3">
                <h2 class="header">Admin Options</h2>
                @include('includes.calendar.admin-deleteevent')
            </div>
        @endif
    </div>
@endsection