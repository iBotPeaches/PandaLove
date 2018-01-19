@extends('app')

@section('content')
    <div class="wrapper style1 first">
        <article class="container" id="top">
            <div class="row">
                <div class="4u">
                    <span class="image fit"><img src="{{ asset('images/panda-logo-medium.png') }}" alt="" /></span>
                </div>
                <div class="8u">
                    <header>
                        <h1>Hi. We are <strong>Panda Love</strong>.</h1>
                    </header>
                    <p>An <strong>Xbox</strong> clan who plays <strong>Halo 5</strong>, <strong>Destiny</strong>, <strong>Destiny 2</strong> and <strong>Overwatch</strong>.</p>

                    <header>
                        <h3>Meet the Team</h3>
                    </header>
                    <a href="{{ URL::action('Destiny\RosterController@getIndex') }}" class="button big scrolly">Destiny</a>
                    <a href="{{ URL::action('Destiny2\RosterController@getIndex') }}" class="button big scrolly">Destiny 2</a>
                    <a href="{{ URL::action('Halo5\RosterController@getIndex') }}" class="button big scrolly">Halo 5</a>
                    <a href="{{ Url::action('Overwatch\RosterController@getIndex') }}" class="button big scrolly">Overwatch</a>
                    <a href="{{ Url::action('Fortnite\RosterController@getIndex') }}" class="button big scrolly">Fortnite</a>
                </div>
            </div>
        </article>
    </div>
    <div class="wrapper style2">
        <article id="work">
            <header>
                <h2>Who are we?</h2>
                <p>A group of friends and friends of friends.</p>
            </header>
            <div class="container">
                <div class="row">
                    <div class="4u">
                        <section class="box style1">
                            <i class="massive pink comments icon desktop-only"></i>
                            <h3>Communication</h3>
                            <p>We plan events through this site, which includes a calendar.</p>
                        </section>
                    </div>
                    <div class="4u">
                        <section class="box style1">
                            <i class="massive pink crosshairs icon desktop-only"></i>
                            <h3>Multiplayer</h3>
                            <p>We aren't pro level by any means, but we don't suck either.</p>
                        </section>
                    </div>
                    <div class="4u">
                        <section class="box style1">
                            <i class="massive pink thumbs up icon desktop-only"></i>
                            <h3>Enhanced Stats</h3>
                            <p>We leverage the APIs that companies provide to build this site.</p>
                        </section>
                    </div>
                </div>
            </div>
            <footer>
                <p>Enjoy looking at our layout of stats? Add your gamertag here.</p>
                <a href="{{ URL::action('AccountController@getIndex') }}" class="button big scrolly">View My Stats</a>
            </footer>
        </article>
    </div>
@endsection