@extends('app')

@section('content')
    @include('includes.usercp.gamertag-verify')

    @if ($user->admin)
        <div class="wrapper style1">
            <article class="container" id="top">
                <div class="row">
                    <div class="12u">
                        <div class="ui info message">
                            Hi admin. We moved the admin panel <a href="{{ action('Backstage\IndexController@getIndex') }}">here.</a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    @endif
@endsection