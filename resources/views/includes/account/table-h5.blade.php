<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th class="ui center aligned" colspan="2">Arena</th>
        <th class="ui center aligned" colspan="2">Warzone</th>
        <th></th>
    </tr>
    <tr>
        <th>Gamertag</th>
        <th class="ui center aligned">Spartan Rank</th>
        <th>Best Playlist</th>
        <th class="ui center aligned">KDA</th>
        <th class="ui center aligned">KD</th>
        <th class="ui center aligned">KDA</th>
        <th class="ui center aligned">KD</th>
        <th class="ui center aligned">Total Games</th>
    </tr>
    </thead>
    <tbody>
    @foreach($h5 as $member)
        <tr>
            <td><a href="{{ URL::action('Halo5\ProfileController@index', array($member->account->seo)) }}">{{ $member->account->gamertag }}</a></td>
            <td class="spartanrank-table center aligned">{{ $member->spartanRank }}</td>
            <td class="bestplaylist-table">
                @define $playlist = $member->record_playlist()
                {!! $playlist == null ? '<i>No highest CSR found</i>' : $playlist->rosterTitle() !!}
            </td>
            <td class="center aligned {{ $member->kad() > 1 ? "positive" : "warning" }} kadr-table">{{ $member->kad() }}</td>
            <td class="center aligned {{ $member->kd() > 1 ? "positive" : "warning" }} kdr-table">{{ $member->kd() }}</td>
            @if (isset($member->warzone) && $member->warzone instanceof \Onyx\Halo5\Objects\Warzone)
                <td class="center aligned {{ $member->warzone->kad() > 1 ? "positive" : "warning" }} kadr-table">{{ $member->warzone->kad() }}</td>
                <td class="center aligned {{ $member->warzone->kd() > 1 ? "positive" : "warning" }} kdr-table">{{ $member->warzone->kd() }}</td>
            @else
                <td class="center aligned">?</td>
                <td class="center aligned">?</td>
            @endif
            <td class="gamesplayed-table ui center aligned">{{ number_format($member->totalGames) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>