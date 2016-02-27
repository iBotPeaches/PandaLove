@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Meet <strong>Panda Love</strong></h1>
                    </header>
                    @include('includes.halo5.roster.table')
                    <p>
                        <a href="{{ action('Halo5\StatsController@getIndex') }}">View our rolling KD/KDA Graphs.</a>
                    </p>
                </div>
            </div>
        </article>
    </div>
@endsection