@if ($event->count() > 0)
    <table class="ui table">
        <thead>
        <tr>
            <th>Gamertag</th>
            <th>Skill Rank</th>
            <th>Total Games</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($event->attendees as $attendee)
            <tr>
                <td>
                    <span class="right floated author">
                        <img class="ui avatar image" src="{{ $attendee->ow->mainCharacter()->image() }}"/>
                        <a href="{{ URL::action('Overwatch\ProfileController@index', [$attendee->account->seo, $attendee->account->accountType]) }}">
                            {{ $attendee->account->gamertag }}
                        </a>
                    </span>
                </td>
                <td>{{ $attendee->ow->comprank }}</td>
                <td>
                    {{ $attendee->ow->games_played }}
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