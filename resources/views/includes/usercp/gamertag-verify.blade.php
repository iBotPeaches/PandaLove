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
                                <strong>Prove ownership of a Gamertag</strong>
                                <p>
                                <div class="ui ordered list">
                                    <span class="item">Sign into your <a target="_blank" href="http://www.xbox.com/en-US/">xbox.com</a> account.</span>
                                    <span class="item">Go to the <a href="https://account.xbox.com/en-US/CustomizeProfile" target="_blank">Settings</a> page.</span>
                                    <span class="item">Copy this code: <strong>{{ $user->google_id }}</strong></span>
                                    <span class="item">Append it / Replace it into your "Bio" section.</span>
                                    <span class="item">Save that field. Don't worry you can change it right back after this validation.</span>
                                    <span class="item">Once done. Enter your Gamertag below and submit.</span>
                                    <span class="item">If we find that text above in your profile. You "claim" ownership of that gamertag.</span>
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
                            <div class="ui info message">
                                <strong>Why should I do this?</strong>
                                <div class="ui ordered list">
                                    <span class="item">Easy access to your profile.</span>
                                    <span class="item">Comment on profiles/games as that gamertag</span>
                                    <span class="item">The Google+ Sign In is just for ease. We won't send you anything or post as you.</span>
                                </div>
                            </div>
                        @else
                            <div class="ui green message">
                                <strong>We know who you are Gamer</strong>
                                <p>
                                    Welcome back <strong><a href="#">{{ $user->account->gamertag }}</a></strong>
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