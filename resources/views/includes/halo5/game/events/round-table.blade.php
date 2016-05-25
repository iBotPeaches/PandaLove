<table class="ui sortable table">
    <thead class="desktop only">
        <tr>
            <th>Spartan</th>
            <th>Kills</th>
            <th>Deaths</th>
            <th>Assists</th>
            <th>KD</th>
            <th>KDA</th>
            <th>Score</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($round as $key => $item)
            @if (! $item['dnf'])
            <tr class="{{ $item['deaths'] == 0 && $item['kills'] != 0 ? 'positive' : null }}">
                <td>
                    <a href="{{ URL::action('Halo5\ProfileController@index', [$data['team'][$key]['seo']]) }}">
                        {{ $data['team'][$key]['name'] }}
                    </a>
                </td>
                <td class="kills-table">{{ $item['kills'] }}</td>
                <td class="deaths-table {{ $item['deaths'] == 0 ? 'no-deaths' : null }}">
                    {!! $item['deaths'] == 0 && $item['kills'] != 0 ? '<i class="smile icon"></i> no deaths' : $item['deaths'] !!}
                </td>
                <td class="assists-table">{{ $item['assists'] }}</td>
                <td class="{{ $item['kda'] >= 1 ? "positive" : "negative" }} kadr-table">{{ round($item['kda'], 2) }}</td>
                <td class="{{ $item['kd'] >= 1 ? "positive" : "negative" }} kdr-table">{{ round($item['kd'], 2) }}</td>
                <td class="score-table">{{ $item['score'] }}</td>
                <td>
                    @if (isset($item['extras']['alpha']))
                        <span class="ui black label">Alpha Zombie</span>
                    @endif
                    @if (isset($item['extras']['infected']))
                        <span class="ui green label">{{ \Onyx\Laravel\Helpers\Text::ordinal($item['extras']['infected']) }} Infected</span>
                    @endif
                    @if (! $match->isTeamGame && $item['deaths'] == 0)
                        <span class="ui blue label">Survived</span>
                    @endif
                </td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>