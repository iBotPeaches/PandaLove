@if ($match->duration != 0)
    <div class="ui inverted black segment">
        {{ $match->gametype->name }} took {{ $match->duration }} to win by {{ $match->winner()->team->name }}
    </div>
@endif
<h3 class="ui header">Quick Facts</h3>
<div class="ui black segment">
    <ul class="ui bulleted list">
        @foreach ($combined['top'] as $score)
            <li class="item">{{ $score['message'] }} - <strong>{{ $score['value'] }}</strong> by
                <a href="{{ action('Halo5\ProfileController@index', [$score['spartan']->account->seo]) }}">{{ $score['spartan']->account->gamertag }}</a>
                <span class="right floated content">
                    <span class="ui horizontal label {{ $score['spartan']->team->team->getSemanticColor() }}">{{ $score['spartan']->team->team->name }}</span>
                </span>
            </li>
        @endforeach
    </ul>
</div>