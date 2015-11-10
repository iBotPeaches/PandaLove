<div class="wrapper style3">
    <article class="container">
        <div class="row">
            <div class="12u">
                <header>
                    <h1>Admin Panel (Halo 5)</h1>
                </header>
            </div>
        </div>
        <div class="row">
            <div class="6u">
                {!! Form::open(['action' => 'AdminController@postAddHalo5Gamertag', 'class' => 'form']) !!}
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
            </div>
        </div>
    </article>
</div>