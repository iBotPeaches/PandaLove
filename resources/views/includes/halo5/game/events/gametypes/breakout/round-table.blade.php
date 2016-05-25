<table class="ui sortable table">
    <thead class="desktop only">
        <tr>
            <th>Spartan</th>
            <th>Kills</th>
            <th>Deaths</th>
            <th>Assists</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($round as $key => $item)
            @if (! $item['dnf'])
            <tr class="{{ $item['deaths'] == 0 && $item['kills'] != 0 ? 'positive' : null }}">
                <td>
                    <a href="{{ URL::action('Halo5\ProfileController@index', [$data['team'][$team_id]['players'][$key]->seo]) }}">
                        {{ $data['team'][$team_id]['players'][$key]->gamertag }}
                    </a>
                </td>
                <td class="kills-table">{{ $item['kills'] }}</td>
                <td class="deaths-table {{ $item['deaths'] == 0 ? 'no-deaths' : null }}">
                    {!! $item['deaths'] == 0 ? '<i class="smile icon"></i> no deaths' : $item['deaths'] !!}
                </td>
                <td class="assists-table">{{ $item['assists'] }}</td>
                <td>
                    @if ($item['deaths'] == 0)
                        <span class="ui blue label">Survived</span>
                    @endif
                    @if (isset($item['extras']['deathCount']))
                        <span class="ui black label">{{ \Onyx\Laravel\Helpers\Text::ordinal($item['extras']['deathCount']) }} death</span>
                    @endif
                </td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>