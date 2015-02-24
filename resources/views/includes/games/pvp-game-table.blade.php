@foreach(array(16, 17) as $team)
    <div class="ui raised blue segment">
        <h3>{{ Helper::teamIdToString($team) }} Team</h3>
        <table class="ui sortable table">
            <thead>
            <tr>
                <th>Guardian</th>
                <th>Character</th>
                <th>Score</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th>Assists</th>
                <th><abbr title="Kill Death Ratio">KDR</abbr></th>
                <th><abbr title="Kills + Assists / Deaths Ratio">KADR</abbr></th>
            </tr>
            </thead>
            <tbody>
            @foreach($game->players as $player)
                @if ($player->completed || $showAll)
                    <tr class="{{ $player->deaths == 0 ? 'positive' : ($player->deaths > $player->kills ? 'negative' : null) }}">
                        <td>
                            <img class="ui avatar image" src="{{ $player->emblem->extra }}" />
                            @if (isset($player->account))
                                <a href="{{ URL::action('ProfileController@index', [$player->account->seo]) }}">
                                    {{ $player->account->gamertag or 'Unknown' }}
                                </a>
                            @else
                                <i>Unknown</i>
                            @endif
                        </td>
                        <td>
                            @if ($player->account)
                                <a href="{{ URL::action('ProfileController@index', [$player->account->seo, $player->character->characterId]) }}">
                                    {{ $player->level }} {{ $player->class }}
                                </a>
                            @else
                                {{ $player->level }} {{ $player->class }}
                            @endif
                        </td>
                        <td class="score-table">{{ $player->score }}</td>
                        <td class="kills-table">{{ $player->kills }}</td>
                        <td class="deaths-table {{ $player->deaths == 0 ? 'no-deaths' : null }}">
                            {!! $player->deaths == 0 ? '<i class="smile icon"></i> no deaths' : $player->deaths !!}
                        </td>
                        <td class="assists-table">{{ $player->assists }}</td>
                        <td class="kdr-table">{{ $player->kdr() }}</td>
                        <td class="kadr-table">{{ $player->kadr() }}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@endforeach

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $(".ui.sortable.table").tablesort();
        });
    </script>
@append