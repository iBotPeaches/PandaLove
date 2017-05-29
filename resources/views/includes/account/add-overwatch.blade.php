{!! Form::open(['action' => '#', 'class' => 'form']) !!}
    @foreach ($errors->destiny->all() as $error)
        <p class="ui red message">{{ $error }}</p>
    @endforeach
    <label>Gamertag / PSN</label>
    <div class="field {{ $errors->destiny->has('gamertag') ? 'error' : '' }}">
        <input type="text" name="gamertag" id="gamertag" placeholder="Gamertag" />
    </div>
    <br />
    <ul class="actions">
        <li><input type="submit" value="Add Overwatch Account" /></li>
    </ul>
{!! Form::close()  !!}