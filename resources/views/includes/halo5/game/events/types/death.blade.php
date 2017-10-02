<?php
/** @var $event \Onyx\Halo5\Objects\MatchEvent */
?>
@if (isset($event->killer) && $event->killer != null && $event->killer->h5_emblem != null)
    <img class="ui avatar image mobile-hidden" src="{{ $event->killer->h5_emblem->getEmblem() }}">
@endif
<div class="content">
    @if (isset($event->killer) && $event->killer != null)
        <a href="{{ action('Halo5\ProfileController@index', [$event->killer->seo]) }}">{{ $event->killer->gamertag }}</a>
    @else
        <a href="#">AI</a>
    @endif
    killed
    @if (isset($event->victim) && $event->victim != null)
        <a href="{{ action('Halo5\ProfileController@index', [$event->victim->seo]) }}">{{ $event->victim->gamertag }}</a>
    @else
        <a href="#">{{ $event->victim_enemy->name or "Unknown Enemy" }}</a>
    @endif
    with a <b>{{ $event->killer_weapon->name }}</b> ({{ $event->distance }}m away)
    <img class="ui avatar image" src="{{ $event->killer_weapon->getImage() }}" />
</div>