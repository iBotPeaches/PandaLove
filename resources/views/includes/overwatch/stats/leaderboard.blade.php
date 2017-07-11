<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th>Gamertag</th>
        <th>Season</th>
        <th>Level</th>
        <th>Max SR</th>
        <th>Playtime</th>
        <th>{{ Onyx\Overwatch\Helpers\String\Text::label($stat) }}</th>
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
            <td class="season-table">
                {{ $hero['stats']['season'] }}
            </td>
            <td class="level-table">
                {{ $hero['stats']['totalLevel'] }}
            </td>
            <td class="rank-table">
                {{ $hero['stats']['max_comprank'] }}
            </td>
            <td class="playtime-table">
                {{ Onyx\Overwatch\Helpers\String\Text::playtimeFormat($hero['playtime']) }}
            </td>
            <td class="{{ $stat }}-table">
                {{ Onyx\Overwatch\Helpers\String\Text::heuristicFormat($stat, array_get($hero['data'], $category.'.'.$stat, 0)) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@section('inline-css')
    <style type="text/css">
        @media (max-width: 736px) {
            .{{ $stat }}-table:before {
                content: "{{ Onyx\Overwatch\Helpers\String\Text::label($stat) }}:";
            }
        }
    </style>
@append