{!! Form::open(['action' => 'CalendarController@deleteEvent', 'class' => 'vertical form']) !!}
<div class="row">
    <div class="12u">
        {!! Form::hidden('event_id', $event->id) !!}
        {!! Form::hidden('_method', 'DELETE') !!}
        <ul class="actions">
            <label>There is no confirm. Be careful son.</label>
            <li><input class="ui large red button" type="submit" value="Delete Event" /></li>
        </ul>
    </div>
</div>
{!! Form::close() !!}