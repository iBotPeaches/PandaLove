{!! Form::open(['action' => 'AccountController@postAddDestinyGamertag', 'class' => 'form']) !!}
    {!! Form::hidden('platform', $data->accountType) !!}
    {!! Form::hidden('gamertag', $data->gamertag) !!}
    <input class="ui fluid {{ $data->color() }} button" type="submit" value="Go to Stats" />
{!! Form::close()  !!}