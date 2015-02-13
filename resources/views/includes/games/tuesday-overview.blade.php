<div class="ui inverted segment">
    {{ $combined['stats']['games'] }} Raids. Completed in {{ $combined['stats']['combinedGameTime'] }}
</div>
<table class="ui sortable table">
    <thead>
    <tr>
        <th>Guardian</th>
        <th>Avg Level</th>
        <th>Games Played</th>
        <th>Kills</th>
        <th>Deaths</th>
        <th>Assists</th>
        <th><abbr title="Kill Death Ratio">KDR</abbr></th>
        <th><abbr title="Kills + Assists / Deaths Ratio">KADR</abbr></th>
    </tr>
    </thead>
    <tbody>
    @foreach($combined['players'] as $player)
        <tr class="{{ $player['deaths'] == 0 ? 'positive' : ($player['deaths'] > $player['kills'] ? 'negative' : null) }}">
            <td>
                @if (isset($player['player']))
                    <a href="{{ URL::action('ProfileController@index', [$player['player']['seo']]) }}">
                        {{ $player['player']['gamertag'] or 'Unknown' }}
                    </a>
                @else
                    <i>Unknown</i>
                @endif
            </td>
            <td>{{ $player['avgLevel'] }}</td>
            <td>{{ $player['count'] }}</td>
            <td>{{ $player['kills'] }}</td>
            <td>{!! $player['deaths'] == 0 ? '<i class="smile icon"></i> no deaths' : $player['deaths'] !!}</td>
            <td>{{ $player['assists'] }}</td>
            <td>{{ $player['kdr'] }}</td>
            <td>{{ $player['kadr'] }}</td>
        </tr>
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