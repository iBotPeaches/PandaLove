<div class="ui raised {{ $team->team->getSemanticColor() }} segment">
    <table class="ui sortable table">
        <thead class="desktop only">
            <tr>
                <th>Gamertag</th>
                <th>Level</th>
                <th>Place</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th>Assists</th>
                <th>KD</th>
                <th>KDA</th>
                <th></th>
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
                    <td><span class="ui label">SR-{{ $player->spartanRank }}</span></td>
                    <td>{!! $player->rank !!}</td>
                    <td>{{ $player->totalKills }}</td>
                    <td class="deaths-table {{ $player->totalDeaths == 0 ? 'no-deaths' : null }}">
                        {!! $player->totalDeaths == 0 && $player->dnf == 0 ? '<i class="smile icon"></i> no deaths' : $player->totalDeaths !!}
                    </td>
                    <td>{{ $player->totalAssists }}</td>
                    <td class="{{ $player->kd() >= 1 ? "positive" : "negative" }} kadr-table">{{ $player->kd() }}</td>
                    <td class="{{ $player->kad() >= 1 ? "positive" : "negative" }} kdr-table">{{ $player->kad() }}</td>
                    <td class="no-sort">
                        <i class="lab icon adv-modal-icon" data-content="Advanced Stats" data-tag="{{ $player->uuid . "-dropdown" }}"></i>
                    </td>
                </tr>
           @endforeach
        </tbody>
    </table>
</div>