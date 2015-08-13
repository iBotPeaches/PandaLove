@if ($event->count() > 0)
    <table class="ui table">
        <thead>
            <tr>
                <th>Gamertag</th>
                <th>Guardian</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->attendees as $attendee)
                <tr>
                    <td>
                        <span class="right floated author">
                            <img class="ui avatar image" src="{{ $attendee->character->emblem->extra}}" />
                            <a href="{{ URL::action('ProfileController@index', [$attendee->account->seo, $attendee->characterId]) }}">
                                {{ $attendee->account->gamertag }}
                            </a>
                        </span>
                    </td>
                    <td>{{ $attendee->character->name() }}</td>
                    <td>
                        @if ($user->id == $attendee->user_id)
                            <a href="#">Cancel</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif