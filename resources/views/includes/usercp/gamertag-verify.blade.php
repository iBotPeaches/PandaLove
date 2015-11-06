<div class="wrapper style1">
    <article class="container" id="top">
        <div class="row">
            <div class="12u">
                <header>
                    <h1>{{ $user->name }}</h1>
                </header>
                {!! Form::open(['action' => 'UserCpController@postGamertagOwnership', 'class' => 'form']) !!}
                <div class="row">
                    <div class="4u">
                        <span class="image fit"><img class="rounded" src="{{ $user->avatar }}" alt="" /></span>
                    </div>
                    <div class="8u">
                        @if ($user->account_id == 0)
                            <div class="ui warning message">
                                <strong>Prove who you are Guardian</strong>
                                <p>
                                <div class="ui ordered list">
                                    <span class="item">Sign into your <a target="_blank" href="https://bungie.net">Bungie.net</a> account</span>
                                    <span class="item">Go to the <a href="https://www.bungie.net/en/Profile#context=settings" target="_blank">Settings</a></span>
                                    <span class="item">Copy this code: <strong>{{ $user->google_id }}</strong></span>
                                    <span class="item">Append it / Replace it into your "Motto" section.</span>
                                    <span class="item">Hit Save on the left hand side.</span>
                                    <span class="item">Once done. Enter Gamertag below and submit.</span>
                                </div>
                                </p>
                            </div>
                            @foreach ($errors->all() as $error)
                                <p class="ui red message">{{ $error }}</p>
                            @endforeach
                            <label>Xbox Live Gamertag</label>
                            <div class="field {{ $errors->has('gamertag') ? 'error' : '' }}">
                                <input type="text" name="gamertag" id="gamertag" placeholder="Gamertag" />
                            </div>
                            <br />
                            <ul class="actions">
                                <li><input type="submit" value="Prove Ownership of Gamertag" /></li>
                            </ul>
                        @else
                            <div class="ui green message">
                                <strong>We know who you are Guardian</strong>
                                <p>
                                    Welcome back <strong><a href="{{ URL::action('Destiny\ProfileController@index', array($user->account->seo)) }}">{{ $user->account->gamertag }}</a></strong>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </article>
</div>