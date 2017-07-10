<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th>Gamertag</th>
        <th>Level</th>
        <th>Max SR</th>
        <th>Playtime</th>
        <th>{{ $stat }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($heros as $hero)
        <tr>
            <td>
                <span class="right floated author">
                    <img class="ui avatar image" src="{{ Onyx\Overwatch\Helpers\Game\Character::image($main['character']) }}"/>
                        <a href="{{ URL::action('Overwatch\ProfileController@index', [$hero['stats']['account']['seo'], $hero['stats']['account']['accountType']]) }}">
                            {{ $hero['stats']['account']['gamertag'] }}
                        </a>
                </span>
            </td>
            <td>
                {{ $hero['stats']['totalLevel'] }}
            </td>
            <td>
                {{ $hero['stats']['max_comprank'] }}
            </td>
            <td>
                {{ $hero['playtime'] }} hour(s)
            </td>
            <td>
                {{ array_get($hero['data'], $category.'.'.$stat, 0) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>