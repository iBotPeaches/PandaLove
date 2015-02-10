<div class="wrapper style2">
    <article class="container">
        <div class="row">
            <div class="12u">
                <header>
                    <h1>Admin Panel</h1>
                </header>
            </div>
        </div>
        <div class="row">
            <div class="6u">
                {!! Form::open(['action' => 'AdminController@postAddGamertag', 'class' => 'form']) !!}
                    @foreach ($errors->all() as $error)
                        <p class="ui red message">{{ $error }}</p>
                    @endforeach
                    <label>Xbox Live Gamertag</label>
                    <div class="field {{ $errors->has('gamertag') ? 'error' : '' }}">
                        <input type="text" name="gamertag" id="gamertag" placeholder="Gamertag" />
                    </div>
                    <br />
                    <ul class="actions">
                        <li><input type="submit" value="Add Gamertag Into System" /></li>
                    </ul>
                {!! Form::close()  !!}
            </div>
            <div class="6u">
                {!! Form::open(['action' => 'AdminController@postAddGame', 'class' => 'form']) !!}
                @foreach ($errors->all() as $error)
                    <p class="ui red message">{{ $error }}</p>
                @endforeach
                <label>Game instanceId</label>
                <div class="field {{ $errors->has('instanceId') ? 'error' : '' }}">
                    <input type="text" name="instanceId" id="instanceId" placeholder="InstanceId of Game" />
                </div>
                <label>Type of Game</label>
                {!! Form::select('type', ['Raid' => 'Raid', 'Flawless' => 'Flawless Raid', 'PVP' => 'PVP']) !!}
                <label>Raid ID (If part of series)</label>
                <div class="field {{ $errors->has('raidTuesday') ? 'error' : '' }}">
                    <input type="text" name="raidTuesday" id="raidTuesday" />
                </div>
                <br />
                <ul class="actions">
                    <li><input type="submit" value="Add Game into Our System" /></li>
                </ul>
                {!! Form::close()  !!}
            </div>
        </div>
    </article>
</div>