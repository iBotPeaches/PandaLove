@if ($games['ResultCount'] != 0)
    <div class="ui special three cards">
        @foreach ($games['Results'] as $key => $result)
            <div class="{{ $result['win'] ? "green" : "red" }} card">
                <a class="ui right corner {{ $result['win'] ? "green" : "red" }} label">
                    <i class="{{ $result['win'] ? "smile" : "frown" }} icon"></i>
                </a>
                <div class="content">
                    <img class="ui avatar image" src="{{ $result['gametype']->getImage() }}">
                    {{ $result['gametype']->name }}
                </div>
                <div class="blurring dimmable image">
                    <div class="ui dimmer">
                        <div class="content">
                            <div class="center">
                                <div class="ui blue button">Go to Game</div>
                            </div>
                        </div>
                    </div>
                    <img src="{{ $result['map']->getImage() }}">
                </div>
                <div class="content">
                    <a class="header" href="{{ URL::action('Halo5\GameController@getGame', ['matchId' => $key]) }}">
                        {{ $result['win'] ? "Victory" : "Loss" }}
                    </a>
                    <div class="meta">
                        Played on {{ $result['map']->name }} on {{ $result['date']->toFormattedDateString() }}
                    </div>
                </div>
                <div class="extra content">
                    <i class="trophy icon"></i>
                    KD: {{ $result['player']->kd() }}
                    KDA: {{ $result['player']->kad() }}
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
    <script type="text/javascript">
        $(function() {
            $('.special.cards .image').dimmer({
                on: 'hover'
            });
        });
    </script>
@append