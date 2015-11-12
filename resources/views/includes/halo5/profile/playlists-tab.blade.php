<h3 class="ui header">Playlists</h3>
@foreach($account->h5->playlists as $playlist)
    <div class="ui segment">
        @if ($playlist->measurementMatchesleft > 0)
            <div class="ui progress">
                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">Matches till Rank Achieved in {{ $playlist->stock->name }}</div>
            </div>
        @endif
    </div>
@endforeach