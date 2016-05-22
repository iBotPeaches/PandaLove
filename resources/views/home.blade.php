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
                    <p>An <strong>Xbox</strong> clan who plays <strong>Halo 5</strong> and <strong>Destiny</strong>.</p>

                    <header>
                        <h3>Meet the Team</h3>
                    </header>
                    <a href="{{ URL::action('Halo5\RosterController@getIndex') }}" class="button big scrolly">Halo 5</a>
                    <a href="{{ URL::action('Destiny\RosterController@getIndex') }}" class="button big scrolly">Destiny</a>
                </div>
            </div>
        </article>
    </div>
    <div class="wrapper style3">
        <article id="work">
            <header>
                <h2>Halo API Competition</h2>
                <p>We added functionality to our clan site to make it easier to submit an entry</p>
            </header>
            <div class="container">
                <div class="row">
                    <div class="4u">
                        <section class="box style1">
                            <span class="icon featured fa-user"></span>
                            <h3>Profile Pages</h3>
                            <p>Each Spartan has a powerful profile page featuring Season history, CSR Percentile and
                            medal/weapon counts.</p>
                        </section>
                    </div>
                    <div class="4u">
                        <section class="box style1">
                            <span class="icon featured fa-crosshairs"></span>
                            <h3>Carnage Reports</h3>
                            <p>Post Game Carnage Reports provide unique overviews to help digest how players played in each game.</p>
                        </section>
                    </div>
                    <div class="4u">
                        <section class="box style1">
                            <span class="icon featured fa-line-chart"></span>
                            <h3>Charts & Time lines</h3>
                            <p>Charts and a time line are used to present kills over time and match events in an easier consumable format.</p>
                        </section>
                    </div>
                </div>
            </div>
            <footer>
                <p>Any Spartan can add their account below.</p>
                <a href="{{ URL::action('AccountController@getIndex') }}" class="button big scrolly">Add My Gamertag</a>
            </footer>
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
                            <span class="icon featured fa-comments-o"></span>
                            <h3>Communication</h3>
                            <p>We plan events through this site, which includes a calendar.</p>
                        </section>
                    </div>
                    <div class="4u">
                        <section class="box style1">
                            <span class="icon featured fa-crosshairs"></span>
                            <h3>Multiplayer</h3>
                            <p>We aren't pro level by any means, but we don't suck either.</p>
                        </section>
                    </div>
                    <div class="4u">
                        <section class="box style1">
                            <span class="icon featured fa-thumbs-o-up"></span>
                            <h3>Enhanced Stats</h3>
                            <p>We leverage the APIs that Bungie & 343 provide to build this site.</p>
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