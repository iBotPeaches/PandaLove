@if ($games['ResultCount'] != 0)
    <div class="ui special stackable three cards">
        @foreach ($games['Results'] as $key => $result)
            <div class="{{ \Onyx\Halo5\Enums\GameResult::getColor($result['win']) }} card">
                <a class="ui right corner {{ \Onyx\Halo5\Enums\GameResult::getColor($result['win']) }} label">
                    <i class="{{ \Onyx\Halo5\Enums\GameResult::getIcon($result['win']) }} icon"></i>
                </a>
                <div class="content">
                    <img class="ui avatar image" src="{{ $result['gametype']->getImage() }}">
                    {{ $result['playlist']->name }}
                </div>
                <div class="blurring dimmable image">
                    <div class="ui dimmer">
                        <div class="content">
                            <div class="center">
                                <a href="{{ $result['url'] }}" class="ui blue button">Go to Game</a>
                            </div>
                        </div>
                    </div>
                    <img src="{{ $result['map']->getImage() }}">
                </div>
                <div class="content">
                    <a class="header" href="{{ $result['url'] }}">
                        {{ \Onyx\Halo5\Enums\GameResult::getTitle($result['win']) }}
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
    <br />
    <div class="ui pagination">
        @if ($page != 0)
            <button class="ui left labeled icon blue button recent-pagination" data-page="{{ $page - 1 }}">
                <i class="left arrow icon"></i>
                Previous
            </button>
        @endif
        <button class="ui right labeled icon  blue button recent-pagination" data-page="{{ $page + 1 }}">
            <i class="right arrow icon"></i>
            Next
        </button>
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