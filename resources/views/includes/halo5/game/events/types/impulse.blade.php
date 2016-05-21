<?php
/** @var $event \Onyx\Halo5\Objects\MatchEvent */
?>
@if (isset($event->killer) && $event->killer != null)
    <img class="ui avatar image mobile-hidden" src="{{ $event->killer->h5_emblem->getEmblem() }}">
@endif
<div class="content">
    @if (isset($event->killer) && $event->killer != null)
        <a href="{{ action('Halo5\ProfileController@index', [$event->killer->seo]) }}">{{ $event->killer->gamertag }}</a>
    @else
        <a href="#">AI</a>
    @endif
    earned <strong>{{ $event->killer_weapon->name }}</strong>
    @if (isset($event->totalTime))
        ({{ \Onyx\Laravel\Helpers\Text::timeDuration($event->totalTime) }})
    @endif
</div>