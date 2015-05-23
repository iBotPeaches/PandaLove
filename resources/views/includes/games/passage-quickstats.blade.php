<div class="ui black segment">
    <ul class="ui list">
        <li>
            PandaLove out scored opponents <strong>{{ $passage['stats']['pandaPts'] }}</strong> to <strong>{{ $passage['stats']['opponentPts'] }}</strong> throughout
            <strong>{{ $combined['stats']['games'] }}</strong> games.
        </li>
        <li>
            PandaLove won <strong>{{ $passage['stats']['pandaWins'] }}</strong> games out of <strong>{{ $combined['stats']['games'] }}</strong> games.
        </li>
        @if ($passage['stats']['blowoutGames'] > 0)
            <li>
                PandaLove had <strong>{{ $passage['stats']['blowoutGames'] }}</strong> perfect games.
            </li>
        @endif
        @if ($passage['buffs']['mercy'])
            <li>
                The loss in this passage was forgiven thanks to the <strong>Mercy of Osiris</strong> buff.
            </li>
        @endif
        @if ($passage['buffs']['favor'])
            <li>
                This passage started with a win, thanks to the <strong>Favor of Osiris</strong> buff.
            </li>
        @endif
        @if ($passage['buffs']['boon'])
            <li>
                This passage had one win count as two, thanks to the <strong>Boon Of Osiris</strong> buff.
            </li>
        @endif
        @if ($passage['buffs']['boon-or-favor'])
            <li>
                There are 8 wins. So either the <strong>Favor of Osiris</strong> or <strong>Boon of Osiris</strong> buff was used.
            </li>
        @endif
        @if (isset($passage['stats']['unbroken']) && is_array($passage['stats']['unbroken']))
            @foreach($passage['stats']['unbroken'] as $unbroken)
                <li>
                    <strong><a href="{{ URL::action('ProfileController@index', [$unbroken['seo']]) }}">{{ $unbroken['gamertag'] }}</a></strong>
                    went unbroken for <strong>{{ $unbroken['count'] }}</strong> {{ $unbroken['count'] > 1 ? 'games' : 'game' }}.
                </li>
            @endforeach
        @endif
    </ul>
</div>