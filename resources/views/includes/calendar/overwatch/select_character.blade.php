{!! Form::open(['action' => ['CalendarController@postRsvpEvent', $event->id], 'class' => 'ui form']) !!}
    {!! Form::hidden('game_id', $event->id) !!}
    <p>
        Click the button below to confirm RSVP for the event.
    </p>
    @foreach ($errors->all() as $error)
        <p class="ui red message">{{ $error }}</p>
    @endforeach
    <div class="actions">
        {!! Form::submit('RSVP to Event') !!}
    </div>
{!! Form::close() !!}

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.ui.checkbox')
                    .checkbox()
            ;
        })
    </script>
@append