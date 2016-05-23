<div class="ui fluid card">
    <div class="image">
        <img src="{{ $match->map->getImage() }}" />
    </div>
    <div class="content">
        <div class="left floated author">
            <img class="ui avatar image" src="{{ $match->gametype->getImage()}}" />
        </div>
        <div class="right floated">
            <span class="header">{{ $match->gametype->name }} on {{ $match->map->name }}</span>
        </div>
    </div>
</div>
<div class="ui inverted black segment">
    <span class="ui avatar image">
        <i class="inverted green icon trophy"></i>
    </span>
    <span class="ui green label">VIP</span> {{ $combined['top']['vip']['spartan']->account->gamertag }}
</div>
@if (count($match->teams) > 1 && $match->isTeamGame)
    @foreach ($match->teams as $team)
        <div class="ui inverted {{ $team->team->getSemanticColor() }} segment">
            <img class="ui avatar image" src="{{ $team->team->getImage() }}">
            <span class="header">{!! $team->label() !!} {{ $team->team->name }} - {{ $team->score }}</span>
        </div>
    @endforeach
@else

@endif
<div class="ui black segment">
    <a href="{{ action('Halo5\GameController@getMatchEvents', [$type, $match->uuid]) }}" class="ui black fluid button">Enhanced Game Look</a>
</div>
@if (\Session::has('previousHaloProfile'))
    <a href="{{ action('Halo5\ProfileController@index', [\Session::get('previousHaloProfile')['seo']]) }}" class="ui">
        Back to {{ \Session::get('previousHaloProfile')['gamertag'] }}'s Profile
    </a>
@endif