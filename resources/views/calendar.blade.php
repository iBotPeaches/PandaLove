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

                    <script type='text/javascript'>

                    $(document).ready(function() {
                        $('#calendar').fullCalendar({
                            googleCalendarApiKey: 'AIzaSyDQiiMhzBdVuGtyp50pVhdH_SQnPoxzcW8',
                            events: {
                                googleCalendarId: 'kqg0rmg06d8vrk6nse9cp32d4s@group.calendar.google.com'
                            }
                        });
                    });

                    </script>
                </div>
            </div>
        </article>
    </div>


@endsection