{!! Form::open(['action' => 'AccountController@postAddFortniteGamertag', 'class' => 'form']) !!}
@foreach ($errors->fortnite->all() as $error)
    <p class="ui red message">{{ $error }}</p>
@endforeach
<div class="two fields">
    <div class="field {{ $errors->fortnite->has('gamertag') ? 'error' : '' }}">
        <label>Epic Username</label>
        <input type="text" name="gamertag" id="gamertag" placeholder="EPIC Username" />
    </div>
</div>
<br />
<ul class="actions">
    <li><input type="submit" value="Add Fortnite Account" /></li>
</ul>
{!! Form::close()  !!}