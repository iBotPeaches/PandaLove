@if ($event->count() > 0)
    <table class="ui table">
        <thead>
        <tr>
            <th>Gamertag</th>
            <th>Spartan Rank</th>
            <th>Total Games</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($event->attendees as $attendee)
            <tr>
                <td>
                        <span class="right floated author">
                            <img class="ui avatar image" src="{{ $attendee->h5->getEmblem() }}" />
                            <a href="{{ URL::action('Halo5\ProfileController@index', [$attendee->account->seo]) }}">
                                {{ $attendee->account->gamertag }}
                            </a>
                        </span>
                </td>
                <td>{{ $attendee->h5->spartanRank }}</td>
                <td>
                    {{ $attendee->h5->totalGames }}
                </td>
                <td>
                    @if ($user->id == $attendee->user_id)
                        <a href="{{ action('CalendarController@getCancelEvent', [$event->id]) }}">Cancel</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif