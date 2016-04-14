<div class="ui two columns grid">
    <div class="column">
        <h5 class="ui header">Killed</h5>
        @if (is_array($player->killed) && count($player->killed) > 0)
            <div class="ui middle aligned selection list">
                @foreach ($player->killed as $killed)
                    <div class="item">
                        <a href="{{ action('Halo5\ProfileController@index', [$killed->seo]) }}">{{ $killed->gamertag }}</a> - <strong>{{ $killed->count }}</strong> {{ $killed->count > 1 ? "times" : "time" }}.
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="column">
        <h5 class="ui header">Killed By</h5>
        @if (is_array($player->killed_by) && count($player->killed_by) > 0)
            <div class="ui middle aligned selection list">
                @foreach ($player->killed_by as $killed)
                    <div class="item">
                        <a href="{{ action('Halo5\ProfileController@index', [$killed->seo]) }}">{{ $killed->gamertag }}</a> - <strong>{{ $killed->count }}</strong> {{ $killed->count > 1 ? "times" : "time" }}.
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>