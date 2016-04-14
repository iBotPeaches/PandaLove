@if ($match->duration != 0)
    <div class="ui inverted black segment">
        {{ $match->gametype->name }} took {{ $match->duration }} to win by {{ $match->winner()->team->name }}
    </div>
@endif
<h3 class="ui header">Quick Facts</h3>
<div class="ui black segment">
    <ul class="ui bulleted list">
        @foreach ($combined['top'] as $score)
            @if ((isset($score['zero']) && $score['value'] != 0) || !isset($score['zero']))
                <li class="ui arena-popup item" data-position="left center"
                    data-variation="wide inverted"
                    data-title="{{ $score['title'] . " - " . $score['spartan']->account->gamertag }}"
                    data-content="{{ $score['tooltip'] }}">{{ $score['message'] }} - <strong>{!! $score['formatted'] !!} </strong> by
                    <a href="{{ action('Halo5\ProfileController@index', [$score['spartan']->account->seo]) }}">{{ $score['spartan']->account->gamertag }}</a>
                    <span class="right floated content">
                        <span class="ui desktop tablet only horizontal label {{ $score['spartan']->team->team->getSemanticColor() }}">{{ $score['spartan']->team->team->name }}</span>
                    </span>
                </li>
            @endif
        @endforeach
    </ul>
</div>