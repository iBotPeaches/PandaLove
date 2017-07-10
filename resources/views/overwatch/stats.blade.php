@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Select a <strong>Hero</strong></h1>
                    </header>
                    <p>
                        Stats will be compared to other Pandas for the Hero chosen.
                    </p>
                    @define $i = 0
                    @foreach ($heros as $key => $hero)
                        @if ($i == 0)
                            <div class="ui eight cards">
                                @endif
                                <div class="card">
                                    <div class="image">
                                        <a href="{{ action('Overwatch\StatsController@getCharacter', [$key]) }}">
                                            <img src="{{ Onyx\Overwatch\Helpers\Game\Character::image($key) }}" />
                                        </a>
                                    </div>
                                    <div class="content">
                                        <a class="header" href="{{ action('Overwatch\StatsController@getCharacter', [$key]) }}">{{ $hero }}</a>
                                    </div>
                                </div>
                                @if ($i == 7)
                                    </div>
                                    @define $i = -1
                                @endif
                        @define $i++
                    @endforeach
                    @if ($i != 0)
                        </div>
                    @endif
            </div>
        </article>
    </div>
@endsection