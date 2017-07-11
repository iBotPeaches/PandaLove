@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="3u">
                    <div class="ui fluid card">
                        <div class="desktop only image">
                            <img src="{{ Onyx\Overwatch\Helpers\Game\Character::image($hero['character']) }}" />
                        </div>
                        <div class="content">
                            <div class="left floated author">
                                <strong>{{ $hero['character'] }}</strong> {{ Onyx\Overwatch\Helpers\String\Text::label($stat) ?? null }}
                            </div>
                        </div>
                    </div>
                    <div class="ui bottom attached segment">
                        @include('includes.overwatch.stats.filter_dropdown', ['hero' => $hero])
                    </div>
                    <div class="ui blue segment">
                        <a href="{{ action('Overwatch\StatsController@getIndex') }}" class="ui blue fluid button">Change Hero</a>
                    </div>
                </div>
                <div class="9u">
                    @include('includes.overwatch.stats.leaderboard', ['heros' => $heros, 'main' => $hero])
                </div>
            </div>
        </article>
    </div>
@endsection