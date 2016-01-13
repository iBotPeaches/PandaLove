<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th>Gamertag</th>
        <th>Spartan Rank</th>
        <th>Best Playlist</th>
        <th>KAD</th>
        <th>KD</th>
        <th>Total Games</th>
    </tr>
    </thead>
    <tbody>
    @foreach($members as $member)
        <tr>
            <td><a href="{{ URL::action('Halo5\ProfileController@index', array($member->seo)) }}">{{ $member->gamertag }}</a></td>
            <td>{{ $member->h5->spartanRank }}</td>
            <td>
                @define $playlist = $member->h5->record_playlist()
                {!! $playlist == null ? '<i>No highest CSR found</i>' : $playlist->rosterTitle() !!}
            </td>
            <td>{{ $member->h5->kad() }}</td>
            <td>{{ $member->h5->kd() }}</td>
            <td>{{ $member->h5->totalGames }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! with(new Onyx\Laravel\SemanticPresenter($members))->render() !!}