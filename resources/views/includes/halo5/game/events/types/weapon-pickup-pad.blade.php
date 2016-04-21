<?php
/** @var $event \Onyx\Halo5\Objects\MatchEvent */
?>
@if (isset($event->killer) && $event->killer != null)
    <img class="ui avatar image" src="{{ $event->killer->h5_emblem->getEmblem() }}">
@endif
<div class="content">
    @if (isset($event->killer) && $event->killer != null)
        <a href="{{ action('Halo5\ProfileController@index', [$event->killer->seo]) }}">{{ $event->killer->gamertag }}</a>
    @else
        <a href="#">AI</a>
    @endif
    has picked up a <b>{{ $event->killer_weapon->name }}</b> from a weapon pad.
    <img class="ui avatar image" src="{{ $event->killer_weapon->getImage() }}" />
</div>