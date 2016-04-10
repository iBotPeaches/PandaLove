<div class="ui raised {{ $team->team->getSemanticColor() }} segment">
    <table class="ui sortable table">
        <thead class="desktop only">
            <tr>
                <th>Gamertag</th>
                <th>Rank</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th>Assists</th>
                <th>KD</th>
                <th>KDA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
                <tr class="{{ $player->totalDeaths == 0 && $player->dnf == 0 ? 'positive' : ($player->totalDeaths > $player->totalKills ? 'negative' : null) }}">
                    <td>
                        @if ($match->gametype->isArena())
                            <span class="right floated author">
                                <img class="ui avatar image arena-popup" src="{{ $player->getArenaImage() }}" data-content="{{ $player->getArenaTooltip() }}"/>
                                <a href="{{ URL::action('Halo5\ProfileController@index', [$player->account->seo]) }}">
                                    {{ $player->account->gamertag }}
                                </a>
                            </span>
                        @else
                            <a href="{{ URL::action('Halo5\ProfileController@index', [$player->account->seo]) }}">
                                {{ $player->account->gamertag }}
                            </a>
                        @endif
                    </td>
                    <td>SR-{{ $player->spartanRank }}</td>
                    <td>{{ $player->totalKills }}</td>
                    <td class="deaths-table {{ $player->totalDeaths == 0 ? 'no-deaths' : null }}">
                        {!! $player->totalDeaths == 0 && $player->dnf == 0 ? '<i class="smile icon"></i> no deaths' : $player->totalDeaths !!}
                    </td>
                    <td>{{ $player->totalAssists }}</td>
                    <td class="center aligned {{ $player->kd() > 1 ? "positive" : "warning" }} kadr-table">{{ $player->kd() }}</td>
                    <td class="center aligned {{ $player->kad() > 1 ? "positive" : "warning" }} kdr-table">{{ $player->kad() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>