{!! Form::open(['action' => 'GameController@postToggleGameVisibility', 'class' => 'vertical form']) !!}
<div class="row">
    <div class="12u">
        {!! Form::hidden('game_id', $game->instanceId) !!}
        <ul class="actions">
            @if ($game->hidden)
                <label>This returns the game back to the history for all people (guests and signed in users alike)</label>
                <li><input class="ui large purple button" type="submit" value="Unhide Game" /></li>
            @else
                <label>This hides the game from the site, except for signed in Pandas. <br />
                    This allows us to add drunk games, shitty games and more for stats but not show them to the world.</label>
                <li><input class="ui large purple button" type="submit" value="Hide Game" /></li>
            @endif
        </ul>
    </div>
</div>
{!! Form::close() !!}