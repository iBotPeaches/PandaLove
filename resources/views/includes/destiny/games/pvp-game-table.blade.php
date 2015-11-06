@foreach($game->pvp->getTeamsInOrder() as $team_id)
    <div class="ui raised {{ $game->pvp->color($team_id) }} segment">
        <h3><span class="ui {{ $game->pvp->color($team_id) }} button fb">{{ $game->pvp->pts($team_id) }} Points</span>{{ \Onyx\Destiny\Helpers\Utils\Team::teamIdToString($team_id) }} Team</h3>
        <table class="ui sortable table">
            <thead class="desktop only">
            <tr>
                <th>Guardian</th>
                <th>Character</th>
                <th>Score</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th>Assists</th>
                @if ($revives)
                    <th>Revives</th>
                @endif
                <th><abbr title="Kill Death Ratio">KDR</abbr></th>
                <th><abbr title="Kills + Assists / Deaths Ratio">KADR</abbr></th>
            </tr>
            </thead>
            <tbody>
            @foreach($game->teamPlayers($team_id) as $player)
                <tr class="{{ $player->deaths == 0 && $player->completed ? 'positive' : ($player->deaths > $player->kills ? 'negative' : null) }}">
                    <td>
                        <img class="ui avatar image" src="{{ $player->emblem->extra }}" />
                        @if ($player->account instanceof \Onyx\Account)
                            @if ($player->account->isPandaLove())
                                <i class="user icon panda-team"></i>
                            @endif
                            <a href="{{ URL::action('Destiny\ProfileController@index', [$player->account->seo]) }}">
                                @if ($player->completed != 0)
                                    {{ $player->account->gamertag or 'Unknown' }}
                                @else
                                    <s>{{ $player->account->gamertag or 'Unknown' }}</s>
                                @endif
                            </a>
                        @else
                            <i>Unknown</i>
                        @endif
                    </td>
                    <td>
                        @if ($player->account && isset($player->gameChar->characterId))
                            <a href="{{ URL::action('Destiny\ProfileController@index', [$player->account->seo, $player->gameChar->characterId]) }}">
                                {{ $player->level }} {{ $player->class }}
                            </a>
                        @else
                            {{ $player->level }} {{ $player->class }}
                        @endif
                    </td>
                    <td class="score-table">{{ number_format($player->score) }}</td>
                    <td class="kills-table">{{ $player->kills }}</td>
                    <td class="deaths-table {{ $player->deaths == 0 ? 'no-deaths' : null }}">
                        {!! $player->deaths == 0 && $player->completed ? '<i class="smile icon"></i> no deaths' : $player->deaths !!}
                    </td>
                    <td class="assists-table">{{ $player->assists }}</td>
                    @if ($revives)
                        <td class="revives-table">{{ $player->revives_given }}</td>
                    @endif
                    <td class="kdr-table">{{ $player->kdr() }}</td>
                    <td class="kadr-table">{{ $player->kadr() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endforeach

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $(".ui.sortable.table").tablesort();

            $('.panda-team')
                    .popup({
                        title: 'PandaLove Member',
                        position: 'top center',
                        inline: true
                    })
            ;
        });
    </script>
@append