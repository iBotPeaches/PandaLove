<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th>Gamertag</th>
        <th>Grimoire</th>
        <th>Char 1</th>
        <th>Char 2</th>
        <th>Char 3</th>
    </tr>
    </thead>
    <tbody>
    @foreach($destiny as $member)
        @if ($member->charactersCount() >= 3 && $member->characterAtPosition(1)->level == \Onyx\Destiny\Enums\LightLevels::$MAX_LEVEL)
            <tr>
                <td>
                    <span class="right floated author">
                        <img class="ui avatar image" src="{{ $member->account->console_image() }}" />
                        <a href="{{ URL::action('Destiny\ProfileController@index', [$member->account->accountType, $member->account->seo]) }}">
                            {{ $member->account->gamertag }}
                        </a>
                    </span>
                </td>
                <td class="grimoire-table">{{ $member->grimoire }}</td>
                <td>
                    <span class="right floated author">
                        <img class="ui avatar image" src="{{ $member->characterAtPosition(1)->emblem->extra}}" />
                        <a href="{{ URL::action('Destiny\ProfileController@index', [$member->account->accountType, $member->account->seo, $member->characterAtPosition(1)->characterId]) }}">
                            {{ $member->characterAtPosition(1)->name() }}
                        </a>
                    </span>
                </td>
                <td>
                    <span class="right floated author">
                        <img class="ui avatar image" src="{{ $member->characterAtPosition(2)->emblem->extra}}" />
                        <a href="{{ URL::action('Destiny\ProfileController@index', [$member->account->accountType, $member->account->seo, $member->characterAtPosition(2)->characterId]) }}">
                            {{ $member->characterAtPosition(2)->name() }}
                        </a>
                    </span>
                </td>
                <td>
                    <span class="right floated author">
                        <img class="ui avatar image" src="{{ $member->characterAtPosition(3)->emblem->extra}}" />
                        <a href="{{ URL::action('Destiny\ProfileController@index', [$member->account->accountType, $member->account->seo, $member->characterAtPosition(3)->characterId]) }}">
                            {{ $member->characterAtPosition(3)->name() }}
                        </a>
                    </span>
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>