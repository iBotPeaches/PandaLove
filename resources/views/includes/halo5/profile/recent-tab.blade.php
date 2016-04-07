@if ($games['ResultCount'] != 0)
    <div class="ui three cards">
        @foreach ($games['Results'] as $result)
            <div class="card">
                <div class="content">
                    <img class="ui avatar image" src="{{ $result['GameType']->getImage() }}">
                    {{ $result['GameType']->name }}
                </div>
                <a class="image" href="{{ URL::action('Halo5\GameController@getGame', ['matchId' => $result['Id']['MatchId']]) }}">
                    <img class="ui image" src="{{ $result['Map']->getImage() }}">
                </a>
                <div class="content">
                    <a class="header" href="{{ URL::action('Halo5\GameController@getGame', ['matchId' => $result['Id']['MatchId']]) }}">
                        {{ $result['Place'] == 1 ? "Victory" : "Loss" }}
                    </a>
                    <div class="meta">
                        Rank: {{ $result['Player']['Rank'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="ui warning message">
        No recent games. This will change once the user has played a game in Halo 5.
    </div>
@endif

@section('inline-js')

@append