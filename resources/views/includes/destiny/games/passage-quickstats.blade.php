<div class="ui black segment">
    <ul class="ui bulleted list">
        <li>
            A <strong>Lighthouse</strong> visit was obtained.
        </li>
        <li>
            PandaLove out scored opponents <strong>{{ $passage['stats']['pandaPts'] }}</strong> to <strong>{{ $passage['stats']['opponentPts'] }}</strong> throughout
            <strong>{{ $combined['stats']['games'] }}</strong> games.
        </li>
        <li>
            PandaLove won <strong>{{ $passage['stats']['pandaWins'] }}</strong> games out of <strong>{{ $combined['stats']['games'] }}</strong> games.
        </li>
        @if ($passage['stats']['differentMaps'])
            <li>
                This was a random map trial, so the maps played were:
                <ul>
                    @foreach($passage['stats']['rMaps'] as $hash => $count)
                        <li>{{ $count }} - {{ \Onyx\Destiny\Helpers\String\Hashes::quick($hash)['title'] }}</li>
                    @endforeach
                </ul>
            </li>
        @endif
        @if ($passage['stats']['blowoutGames'] > 0)
            <li>
                PandaLove had <strong>{{ $passage['stats']['blowoutGames'] }}</strong> perfect {{ $passage['stats']['blowoutGames'] > 1 ? 'games' : 'game' }}.
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
        @if ($passage['buffs']['quitout'] != 0)
            <li>
                There {{ $passage['buffs']['quitout'] > 1 ? 'were' : 'was' }} <strong>{{ $passage['buffs']['quitout'] }}</strong> {{ $passage['buffs']['quitout'] > 1 ? 'games' : 'game' }} that the enemy team quit out before it began.
            </li>
        @endif
        @if (isset($passage['stats']['unbroken']) && is_array($passage['stats']['unbroken']))
            @foreach($passage['stats']['unbroken'] as $unbroken)
                <li>
                    <strong><a href="{{ URL::action('Destiny\ProfileController@index', [$unbroken['type'], $unbroken['seo']]) }}">{{ $unbroken['gamertag'] }}</a></strong>
                    went unbroken for <strong>{{ $unbroken['count'] }}</strong> {{ $unbroken['count'] > 1 ? 'games' : 'game' }}.
                </li>
            @endforeach
        @endif
    </ul>
</div>