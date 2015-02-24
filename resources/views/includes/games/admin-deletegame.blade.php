{!! Form::open(['action' => 'GameController@deleteGame', 'class' => 'vertical form']) !!}
    <div class="row">
        <div class="12u">
            {!! Form::hidden('game_id', $game->instanceId) !!}
            {!! Form::hidden('_method', 'DELETE') !!}
            <ul class="actions">
                <label>There is no confirm. Be careful son.</label>
                <li><input class="ui large red button" type="submit" value="Delete Game" /></li>
            </ul>
        </div>
    </div>
{!! Form::close() !!}