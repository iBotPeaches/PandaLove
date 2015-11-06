<div class="ui inverted segment">
    {{ $combined['stats']['games'] }} Raids. Completed in {{ $combined['stats']['combinedGameTime'] }}
</div>
<table class="ui sortable table">
    <thead class="desktop only">
    <tr>
        <th>Guardian</th>
        <th>Avg Level</th>
        <th>Games Played</th>
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
    @foreach($combined['players'] as $player)
        <tr class="{{ $player['deaths'] == 0 ? 'positive' : ($player['deaths'] > $player['kills'] ? 'negative' : null) }}">
            <td>
                @if (isset($player['player']))
                    @if ($player['player']['isPandaLove'])
                        <i class="user icon panda-team"></i>
                    @endif
                    <a href="{{ URL::action('ProfileController@index', [$player['player']['seo']]) }}">
                        {{ $player['player']['gamertag'] or 'Unknown' }}
                    </a>
                @else
                    <i>Unknown</i>
                @endif
            </td>
            <td class="avglevel-table">{{ $player['avgLevel'] }}</td>
            <td class="gamesplayed-table">{{ $player['count'] }}</td>
            <td class="kills-table">{{ $player['kills'] }}</td>
            <td class="deaths-table {{ $player['deaths'] == 0 ? 'no-deaths' : null }}">
                {!! $player['deaths'] == 0 ? '<i class="smile icon"></i> no deaths' : $player['deaths'] !!}
            </td>
            <td class="assists-table">{{ $player['assists'] }}</td>
            @if ($revives)
                <td class="revives-table">{{ $player['revives_given'] or 0}}</td>
            @endif
            <td class="kdr-table">{{ $player['kdr'] }}</td>
            <td class="kadr-table">{{ $player['kadr'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

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