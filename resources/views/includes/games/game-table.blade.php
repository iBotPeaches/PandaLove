<table class="ui sortable table">
    <thead>
    <tr>
        <th>Guardian</th>
        <th>Character</th>
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
                <td>{{ $player->kills }}</td>
                <td>{!! $player->deaths == 0 ? '<i class="smile icon"></i> no deaths' : $player->deaths !!}</td>
                <td>{{ $player->assists }}</td>
                <td>{{ $player->kdr() }}</td>
                <td>{{ $player->kadr() }}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $(".ui.sortable.table").tablesort();
        });
    </script>
@append