{!! Form::open(['action' => 'AccountController@postAddOverwatchGamertag', 'class' => 'form']) !!}
    @foreach ($errors->all() as $error)
        <p class="ui red message">{{ $error }}</p>
    @endforeach
    <label>Gamertag / PSN / PC</label>
    <div class="field {{ $errors->has('gamertag') ? 'error' : '' }}">
        <input type="text" name="gamertag" id="gamertag" placeholder="Gamertag" />
    </div>
    <br />
    <ul class="actions">
        <li><input type="submit" value="Add Overwatch Account" /></li>
    </ul>
{!! Form::close()  !!}