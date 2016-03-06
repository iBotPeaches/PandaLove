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
    @foreach($members as $member)
        <tr>
            <td><a href="{{ URL::action('Halo5\ProfileController@index', array($member->seo)) }}">{{ $member->gamertag }}</a></td>
            <td class="spartanrank-table center aligned">{{ $member->h5->spartanRank }}</td>
            <td class="bestplaylist-table">
                @define $playlist = $member->h5->record_playlist()
                {!! $playlist == null ? '<i>No highest CSR found</i>' : $playlist->rosterTitle() !!}
            </td>
            <td class="center aligned {{ $member->h5->kad() > 1 ? "positive" : "warning" }} kadr-table">{{ $member->h5->kad() }}</td>
            <td class="center aligned {{ $member->h5->kd() > 1 ? "positive" : "warning" }} kdr-table">{{ $member->h5->kd() }}</td>
            <td class="center aligned {{ $member->h5->warzone->kad() > 1 ? "positive" : "warning" }} kadr-table">{{ $member->h5->warzone->kad() }}</td>
            <td class="center aligned {{ $member->h5->warzone->kd() > 1 ? "positive" : "warning" }} kdr-table">{{ $member->h5->warzone->kd() }}</td>
            <td class="gamesplayed-table ui center aligned">{{ number_format($member->h5->totalGames) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! with(new Onyx\Laravel\SemanticPresenter($members))->render() !!}