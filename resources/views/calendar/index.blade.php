@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1><strong>Panda Love's</strong> Calendar</h1>
                    </header>
                    <div id='calendar'></div>
                </div>
            </div>
            <div class="desktop only">
                Legend:
                <button class="ui tiny green button">Destiny</button>
                <button class="ui tiny red button">Halo 5</button>
                <button class="ui tiny black button">Overwatch</button>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type='text/javascript'>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                firstDay: 1,
                events: " {{ URL::action('CalendarController@getEvents') }}"
            });
        });
    </script>
    <script src="{{ asset("js/moment.min.js") }}"></script>
    <script src="{{ asset("js/fullcalendar.min.js") }}"></script>
@append

@section('inline-css')
    <link rel='stylesheet' href="{{ asset('css/fullcalendar.min.css') }}" />
@append