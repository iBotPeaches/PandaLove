{!! Form::open(['action' => 'AccountController@postAddHalo5Gamertag', 'class' => 'form']) !!}
    @foreach ($errors->halo5->all() as $error)
        <p class="ui red message">{{ $error }}</p>
    @endforeach
    <label>Xbox Live Gamertag</label>
    <div class="field {{ $errors->halo5->has('gamertag') ? 'error' : '' }}">
        <input type="text" name="gamertag" id="gamertag" placeholder="Gamertag" />
    </div>
    <br />
    <ul class="actions">
        <li><input type="submit" value="Add Halo 5 Account" /></li>
    </ul>
{!! Form::close()  !!}