{!! Form::open(['action' => 'AccountController@postAddFortniteGamertag', 'class' => 'form']) !!}
@foreach ($errors->fortnite->all() as $error)
    <p class="ui red message">{{ $error }}</p>
@endforeach
<div class="two fields">
    <div class="field {{ $errors->fortnite->has('gamertag') ? 'error' : '' }}">
        <label><a target="_blank" href="https://www.epicgames.com/account/connected">Epic</a> Username</label>
        <input type="text" name="gamertag" id="gamertag" placeholder="EPIC Username" />
    </div>
    <div class="field {{ $errors->fortnite->has('platform') ? 'error' : '' }}">
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
</div>
<br />
<ul class="actions">
    <li><input type="submit" value="Add Fortnite Account" /></li>
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