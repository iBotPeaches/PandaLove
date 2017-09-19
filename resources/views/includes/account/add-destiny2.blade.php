{!! Form::open(['action' => 'AccountController@postAddDestiny2Gamertag', 'class' => 'form']) !!}
@foreach ($errors->destiny2->all() as $error)
    <p class="ui red message">{{ $error }}</p>
@endforeach
<label>Gamertag / PSN / PC</label>
<div class="field {{ $errors->destiny2->has('gamertag') ? 'error' : '' }}">
    <input type="text" name="gamertag" id="gamertag" placeholder="Gamertag" />
</div>
<div class="field {{ $errors->has('platform') ? 'error' : '' }}">
    <label>Platform</label>
    <div class="ui selection dropdown">
        <input type="hidden" name="platform">
        <i class="dropdown icon"></i>
        <div class="default text">Platform</div>
        <div class="menu">
            <div class="item" data-value="{{ \Onyx\XboxLive\Enums\Console::Xbox }}">Xbox</div>
            <div class="item" data-value="{{ \Onyx\XboxLive\Enums\Console::PSN }}">PSN</div>
            <div class="item" data-value="{{ \Onyx\XboxLive\Enums\Console::PC }}">PC</div>
        </div>
    </div>
</div>
<br />
<ul class="actions">
    <li><input type="submit" value="Add Destiny 2 Account" /></li>
</ul>
{!! Form::close()  !!}

@section('inline-js')
    <script type="text/javascript">
        $(document).on('ready', function() {
            $('.ui.dropdown')
                .dropdown()
            ;
        });
    </script>
@append