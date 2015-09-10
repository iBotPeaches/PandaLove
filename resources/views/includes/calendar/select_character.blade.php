{!! Form::open(['action' => ['CalendarController@postRsvpEvent', $event->id], 'class' => 'ui form']) !!}
    {!! Form::hidden('game_id', $event->id) !!}
    <p>
        Select your character.
    </p>
    @foreach ($errors->all() as $error)
        <p class="ui red message">{{ $error }}</p>
    @endforeach
    <div class="grouped fields">
        @foreach ($user->account->characters as $character)
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="character" value="{{ $character->characterId }}">
                    <label>{{ $character->name() . " - Light (" . $character->light . ")" }}</label>
                </div>
            </div>
        @endforeach
    </div>
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