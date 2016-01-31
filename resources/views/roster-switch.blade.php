@extends('app')

@section('content')
    <div class="wrapper style1 first">
        <article class="container" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Hi. Select a <strong>Game</strong>.</h1>
                    </header>
                    <p>We have clan members in both <strong>Halo 5</strong> and <strong>Destiny</strong>.</p>
                    <a href="{{ URL::action('Halo5\RosterController@getIndex') }}" class="button big scrolly">Halo 5</a>
                    <a href="{{ URL::action('Destiny\RosterController@getIndex') }}" class="button big scrolly">Destiny</a>
                </div>
            </div>
        </article>
    </div>
@endsection